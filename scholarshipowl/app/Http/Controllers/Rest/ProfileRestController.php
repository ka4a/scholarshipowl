<?php namespace App\Http\Controllers\Rest;

use App\Entity\Country;
use App\Entity\EligibilityCache;
use App\Entity\Marketing\SubmissionSources;
use App\Entity\Profile;
use App\Entity\Repository\EligibilityCacheRepository;
use App\Entity\Resource\ProfileResource;
use App\Events\Account\UpdateAccountEvent;
use App\Http\Controllers\RestController;
use App\Jobs\UpdateSubmissions;
use App\Rest\Requests\ProfileUpdateRequest;
use App\Services\Account\AccountService;
use App\Services\Marketing\SubmissionService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Illuminate\Http\Request;

class ProfileRestController extends RestController
{
    /**
     * @var AccountService
     */
    protected $as;

    /**
     * @var SubmissionService
     */
    protected $ss;

    /**
     * ProfileRestController constructor.
     *
     * @param EntityManager  $em
     * @param AccountService $as
     * @param SubmissionService $ss
     */

    public function __construct(EntityManager $em, AccountService $as, SubmissionService $ss)
    {
        parent::__construct($em);
        $this->as = $as;
        $this->ss = $ss;
    }

    /**
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository(Profile::class);
    }

    /**
     * @return ProfileResource
     */
    public function getResource()
    {
        return new ProfileResource();
    }

    /**
     * @param Request $request
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getBaseIndexQuery(Request $request)
    {
        return $this->getRepository()->createQueryBuilder('p');
    }

    /**
     * @param Request $request
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getBaseIndexCountQuery(Request $request)
    {
        $qb = $this->getBaseIndexQuery($request);
        return $qb->select($qb->expr()->count('p.account'));
    }

    /**
     * @param ProfileUpdateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProfileUpdateRequest $request)
    {
        $profile = $request->profile();
        $fields = $request->fields();
        $exclude = [];
        $coregs = false;

        if ($request->exists('dateOfBirth')) {
            $profile->setDateOfBirth(new \DateTime($request->get('dateOfBirth')));
            $exclude[] = 'dateOfBirth';
            $fields['dateOfBirth'] = 'dateOfBirth';
        }

        if ($request->exists('password')) {
            $this->as->updatePassword($profile->getAccount(), $request->get('password'));
            $exclude[] = 'password';
        }

        if($request->exists('countryCode')) {
            $profile->setCountry(Country::findByCountryCode($request->get('countryCode')));
            $exclude[] = 'countryCode';
        }

        if($coregs = $request->get('coregs', false)){
            $source = is_mobile() ? SubmissionSources::MOBILE : SubmissionSources::DESKTOP;

            $this->ss->addSubmissions($coregs, $profile->getAccount(), $request->getClientIp(), $source);
            dispatch(new UpdateSubmissions($profile->getAccount()));
        }

        foreach ($this->updateEntity($profile, $request, $exclude) as $field) {
            $fields[$field] = $field;
        }

        $this->em->flush();

        \Event::dispatch(new UpdateAccountEvent($profile->getAccount(), $request->header('Referer')));

        if ($request->get('generate_one_time_token')) {
            // this token token needed for Android/IOS native apps,
            // they use webview for registration flow, token suppose to be exchanged for JWT one
            $token = \Str::random(8);
            $accountId = $profile->getAccount()->getAccountId();
            \Cache::put("one-time-token-{$accountId}", $token, 2 * 60); // for later retrieving token
            \Cache::put($token, $accountId, 2 * 60); // for later exchanging token
        }

        return $this->jsonResponse($profile, null, $this->getResource()->setOnly($fields));
    }
}
