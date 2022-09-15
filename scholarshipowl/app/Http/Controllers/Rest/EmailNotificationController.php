<?php
namespace App\Http\Controllers\Rest;

use App\Entity\Account;
use App\Entity\Package;
use App\Http\Controllers\RestController;
use App\Http\Exceptions\cantFindDefaultEmailForNotification;
use App\Services\EmailNotificationService;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;

class EmailNotificationController extends RestController
{

    const CACHE_TAG = 'email.notification';
    /**
     * @var EmailNotificationService
     */
    protected $emailNotificationService;

    protected function getRepository()
    {
        // TODO: Implement getRepository() method.
    }

    protected function getBaseIndexQuery(Request $request)
    {
        // TODO: Implement getBaseIndexQuery() method.
    }

    protected function getBaseIndexCountQuery(Request $request)
    {
        // TODO: Implement getBaseIndexCountQuery() method.
    }

    protected function getResource()
    {
        // TODO: Implement getResource() method.
    }

    public function __construct(EntityManager $em, EmailNotificationService $emailNotificationService)
    {
        parent::__construct($em);
        $this->emailNotificationService = $emailNotificationService;
    }

    /**
     * @param $elitePackageId
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws cantFindDefaultEmailForNotification
     */
    public function sendEmail($elitePackageId)
    {
        $response = $this->jsonSuccessResponse();
        /**
         * @var Package $elitePackage
         */
        $elitePackage = $this->em->getRepository(Package::class)->find($elitePackageId);
        if(!is_null($elitePackage)){
            $response->setData([
                'success_message' => $elitePackage->getSuccessMessage(),
                'success_title' => $elitePackage->getSuccessTitle()
            ]);
        }
        /** @var Account $account */
        $account = \Auth::user();
        $profile = $account->getProfile();

        $salesTeamEmail = app('config')['scholarshipowl.mail.sales_team.email'];

        if(is_null($salesTeamEmail)){
            throw new cantFindDefaultEmailForNotification();
        }

        $requestTime = Carbon::now('Europe/Belgrade');
        try {
            $cache = \Cache::tags(self::CACHE_TAG)->get($account->getAccountId());
            if(empty($cache)) {
                $this->emailNotificationService->sendEmailNotification(
                    $salesTeamEmail,
                    [
                        'account_id'   => $account->getAccountId(),
                        'name'         => $profile->getFirstName(),
                        'phone'        => $profile->getPhone(),
                        'email'        => $account->getEmail(),
                        'request_time' => $requestTime->toDateTimeString()
                    ]
                );
                \Cache::tags(self::CACHE_TAG)->put($account->getAccountId(), $account->getAccountId(), 10*60);
            }
        } catch (\Exception $e) {
            \Sentry::captureException($e);
            \Log::error($e);
            $response = $this->jsonErrorResponse($e->getMessage());
        }

        return $response;
    }
}