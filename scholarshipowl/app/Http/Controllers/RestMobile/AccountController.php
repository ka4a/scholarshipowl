<?php

namespace App\Http\Controllers\RestMobile;

use App\Entity\Account;
use App\Entity\Citizenship;
use App\Entity\Country;
use App\Entity\Profile;
use App\Entity\Repository\AccountRepository;
use App\Entity\Resource\AccountResource;
use App\Events\Account\UpdateAccountEvent;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RestController;
use App\Http\Traits\JsonResponses;
use App\Services\Account\ForgotPasswordService;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Facebook\Exceptions\FacebookResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Services\Account\AccountService;
use ScholarshipOwl\Data\Entity\Account\Profile as ProfileService;
use ScholarshipOwl\Data\Service\Info\InfoServiceFactory;
use Illuminate\Mail\Message;

class AccountController extends Controller
{
    use JsonResponses;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var AccountService
     */
    protected $accountService;


    /**
     * RestMobileAccountController constructor.
     * @param EntityManager $em
     * @param AccountService $accountService
     */
    public function __construct(
        EntityManager $em,
        AccountService $accountService
    )
    {
        $this->em = $em;
        $this->accountService = $accountService;
    }

    /**
     * @return AccountRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository(Account::class);
    }

    /**
     * @return mixed
     */
    protected function getResource()
    {
        return new AccountResource();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    function show()
    {
        $account = \Auth::user();

        return $this->jsonResponse($account);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        try {
            $account = $this->accountService->registerAccount($request->all());

            return $this->jsonResponse($account, ['token' => \JWTAuth::fromUser($account)]);
        } catch (FacebookResponseException $e) {
            return response()->json(['status' => $e->getHttpStatusCode(), 'error' => $e->getMessage()], $e->getHttpStatusCode());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request)
    {
        $account = \Auth::user();
        $this->accountService->updateProfile($account, $request->all());

        return $this->jsonResponse($account);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete()
    {
        $account = \Auth::user();
        $this->em->remove($account);
        $this->em->flush();

        return $this->jsonSuccessResponse();
    }

    /**
     * Options to build registration form
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function formOptions()
    {
        // Put `Undecided` to the bottom of array
        $degreeType = InfoServiceFactory::get("DegreeType")->getAll(false);
        $undecided = $degreeType[1];
        unset($degreeType[1]);
        $degreeType[1] = $undecided;
        return $this->jsonSuccessResponse([
            "genders"      => Profile::genders(),
            "citizenships" => Citizenship::options(['country' => Country::USA]),
            "ethnicities"  => InfoServiceFactory::get("Ethnicity")->getAll(false),
            "gpas"         => array("N/A" => "N/A") + Profile::gpas(),
            "degrees"      => InfoServiceFactory::get("Degree")->getAll(false),
            "degreeTypes"  => $degreeType,
            "careerGoals"  => InfoServiceFactory::get("CareerGoal")->getAll(false),
            "schoolLevels" => InfoServiceFactory::getArrayData("SchoolLevel"),
            "studyOnline"  => Profile::studyOnlineOptions(),
            "states"       => InfoServiceFactory::get("State")->getAll(false)
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function passwordChange(Request $request)
    {
        /** @var Account $account */
        $account = \Auth::user();

        $this->validate($request, [
            'passwordCurrent' => 'required',
            'passwordNew' => 'required|min:6',
        ]);

        if (!\Hash::check($request->get('passwordCurrent'), $account->getPassword())) {
            return $this->jsonErrorResponse('Oops! Your current password is incorrect.', 403);
        }

        $this->accountService->updatePassword($account, $request->get('passwordNew'));
        \EntityManager::flush($account);

        return  $this->jsonSuccessResponse([]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function passwordReset(Request $request, ForgotPasswordService $forgotPasswordService)
    {
        /** @var Account $account */
        $account = \Auth::user();

        $this->validate($request, [
            'token' => 'required',
            'passwordNew' => 'required|min:6',
        ]);

        $forgotPasswordEntity = $forgotPasswordService->findByToken($request->get('token'));
        if (!$forgotPasswordEntity || $forgotPasswordEntity->getExpireDate() < Carbon::now()) {
            return $this->jsonErrorResponse(['token' => 'Password reset token is expired or invalid.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $account = $forgotPasswordEntity->getAccount();
        $this->accountService->updatePassword($account, $request->get('passwordNew'));
        \EntityManager::flush($account);
        $forgotPasswordService->expire($forgotPasswordEntity);

        return  $this->jsonSuccessResponse([]);
    }


    public function appInstalled(Request $request)
    {
        /**
         * @var Account $account
         */
        $account = \Auth::user();
        return $this->updateAppInstalledFlag($account,1);
    }

    public function appUninstalled(Request $request)
    {
        /**
         * @var Account $account
         */
        $account = \Auth::user();
        return $this->updateAppInstalledFlag($account,0);
    }

    /**
     * @param Account $account
     * @param int $flag
     * @return \Illuminate\Http\JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function updateAppInstalledFlag(Account $account, int $flag)
    {
        $account->setAppInstalled($flag);

        $this->em->persist($account);
        $this->em->flush();

        // Fire Event
        \Event::dispatch(new UpdateAccountEvent($account));

        return $this->jsonResponse($account);
    }

    /**
     * @param null $data
     * @param null $meta
     * @param ResourceInterface|null $resource
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonResponse($data = null, $meta = null, ResourceInterface $resource = null)
    {
        $resource = $resource ?: $this->getResource();

        if (is_array($data)) {
            $data = (new ResourceCollection($resource, $data))->toArray();
        } elseif (is_object($data)) {
            $data = $resource->setEntity($data)->toArray();
        }

        return $this->jsonDataResponse($data, $meta);
    }
}

