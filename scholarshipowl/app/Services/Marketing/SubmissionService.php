<?php


namespace App\Services\Marketing;


use App\Entity\Account;
use App\Entity\Marketing\CoregPlugin;
use App\Entity\Marketing\Submission;
use App\Entity\Marketing\SubmissionSources;
use App\Entity\Profile;
use App\Http\Traits\ValidatesArray;
use Carbon\Carbon;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;

class SubmissionService
{
    use ValidatesArray;

    const VALIDATE_BERECRUITED = "Berecruited";

    /** @var  CoregService */
    private $coregService;

    /**
     * @var EntityManager
     */
    private static $entityManager;

    /**
     * SubmissionService constructor.
     * @param EntityManager $em
     * @param CoregService  $cs
     */
    public function __construct(EntityManager $em, CoregService $cs)
    {
        self::$entityManager = $em;
        $this->coregService = $cs;
    }

    /**
     * Re-open EntityManager if it was closed.
     * Need for prevent error `Doctrine\\ORM\\ORMException' with message 'The EntityManager is closed`
     * @return EntityManager
     * @throws \Doctrine\ORM\ORMException
     */
    private static function getEntityManager() {
        if (!self::$entityManager->isOpen()) {
            self::$entityManager = self::$entityManager->create(
                self::$entityManager->getConnection(), self::$entityManager->getConfiguration());
        }

        return self::$entityManager;
    }

    /**
     * @return array
     */
    protected function getValidationRules(): array
    {
        return [
            static::VALIDATE_BERECRUITED => [
                "parent_first_name" => "required",
                "parent_last_name" => "required",
                "parent_email" => "required",
                "parent_phone_number" => "required",
                "graduation_year" => "required",
                "sport_id" => "required",
            ],
        ];
    }

    /**
     * @param array $params
     * @param array $limit
     *
     * @return Paginator
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function searchSubmissions($params = array(), $limit = [])
    {
        $criteria = new Criteria();

        if (!empty($params["first_name"])) {
            $criteria->andWhere(Criteria::expr()->contains("p.firstName", $params["first_name"]));
        }
        if (!empty($params["last_name"])) {
            $criteria->andWhere(Criteria::expr()->contains("p.lastName", $params["last_name"]));
        }
        if (!empty($params["email"])) {
            $criteria->andWhere(Criteria::expr()->contains("a.email", $params["email"]));
        }
        if (!empty($params["name"])) {
            $criteria->andWhere(Criteria::expr()->in("s.name", $params["name"]));
        }
        if (!empty($params["status"])) {
            $criteria->andWhere(Criteria::expr()->in("s.status", $params["status"]));
        }
        if (!empty($params["send_date_from"])) {
            $criteria->andWhere(Criteria::expr()->gt("s.sendDate", $params["send_date_from"]));
        }
        if (!empty($params["send_date_to"])) {
            $criteria->andWhere(Criteria::expr()->lte("s.sendDate", $params["send_date_to"]));
        }

        $query = $this->fetchSubmissionsByCriteria($criteria);

        if(!empty($limit)){
            $query->setFirstResult($limit[0] * $limit[1])
                ->setMaxResults($limit[1]);
        }

        $submissions = new Paginator($query->getQuery(), false);

        return $submissions;
    }

    /**
     * @param     $coregs
     * @param     $account
     * @param     $ip
     * @param int $source
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addSubmissions($coregs, $account, $ip, $source = SubmissionSources::DESKTOP)
    {
        $em = self::getEntityManager();
        $submission = null;
        $account = $account instanceof Account ? $account : $em->find(Account::class, $account);
        foreach ($coregs as $key => $coreg) {
            if (isset($coreg["checked"]) && $coreg["checked"] == 1) {

                //BirdDog's different coregs should have the name BirdDogs
                if(in_array($key, CoregPlugin::getBirdDogsCoregList())){
                    $key = "BirdDog";
                }

                $searchCriteria = ["account" => $account];
                if (isset($coreg['id'])) {
                    $searchCriteria['coregPlugin'] = $coreg['id'];
                }

                if (!$em->getRepository(Submission::class)->findOneBy($searchCriteria)) {
                    if (isset($this->getValidationRules()[$key])) {
                        $this->validate($coreg["extra"], $this->getValidationRules()[$key]);
                    }

                    $submission = new Submission();

                    $submission->setAccount($account);
                    $submission->setIpAddress($ip);
                    $submission->setName(ucfirst($key));
                    $submission->setSource(SubmissionSources::convert($source));
                    $submission->setStatus(Submission::STATUS_INCOMPLETE);
                    $submission->setParams(isset($coreg["extra"]) ? json_encode($coreg["extra"]) : "");

                    if (isset($coreg['id'])) { //eventually all coregs must have an id, it's just for the transition to a new logic
                        $submission->setCoregPlugin($em->getRepository(CoregPlugin::class)->findOneBy(['id' => $coreg['id']]));
                    }

                    self::getEntityManager()->persist($submission);
                }
            }
        }

        self::getEntityManager()->flush();
        return $submission;
    }

    /**
     * @param      $name
     * @param      $status
     * @param null $batch
     *
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     */
    public function getSubmissionsAccountsByNameAndStatus($name, $status, $batch = null)
    {
        $plugin = $this->coregService->getCoregPluginByName($name);

        $limit = false;

        if ($plugin && (($pluginLimit = $this->coregService->getRemainingPluginCap($plugin)) !== false) || $batch) {
            $limit = (($batch > $pluginLimit) ? $batch : $pluginLimit);
        }

        $result = self::getEntityManager()->getRepository(Submission::class)->createQueryBuilder("s")
            ->leftJoin(Account::class, "a", 'WITH', 'a.accountId = s.account')
            ->leftJoin(Profile::class, "p", 'WITH', 'p.account = s.account')
            ->innerJoin(CoregPlugin::class, "cp", 'WITH', 'cp.id = s.coregPlugin AND cp.justCollect = 0')
            ->where("s.name = ?1")
            ->andWhere("s.status = ?2")
            ->andWhere("a.sellInformation != 1")
            ->setParameters([
                1 => $name,
                2 => $status
            ])->setMaxResults($limit ?: 1000)->getQuery()->execute();

        return $result;
    }

    public function updateSuccessSubmission($submissionId, $response)
    {
        return $this->updateSubmissionByStatus($submissionId, $response, Submission::STATUS_SUCCESS);
    }

    public function updateErrorSubmission($submissionId, $response)
    {
        return $this->updateSubmissionByStatus($submissionId, $response, Submission::STATUS_ERROR);
    }

    public function updateErrorSubmittionSubmission($submissionId, $response)
    {
        return $this->updateSubmissionByStatus($submissionId, $response, Submission::STATUS_ERROR_SUBMISSION);
    }

    public function updateErrorValidationSubmission($submissionId, $response)
    {
        return $this->updateSubmissionByStatus($submissionId, $response, Submission::STATUS_ERROR_VALIDATION);
    }

    /**
     * @param Account $account
     * @return Submission[]
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function getSubmissionsByAccountId(Account $account)
    {
        $criteria = new Criteria();
        $criteria->andWhere(Criteria::expr()->eq("a", $account->getAccountId()));
        $query = $this->fetchSubmissionsByCriteria($criteria);
        /**
         * @var Submission[] $submissions
         */
        $submissions = $query->getQuery()->execute();
        return $submissions;
    }

    /**
     * @param Account $account
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function removePendingSubmissionByAccountId(Account $account)
    {
        $em = self::getEntityManager();
        $submissions = $this->getSubmissionsByAccountId($account);
        foreach ($submissions as $submission) {
            $em->remove($submission);
        }
        $em->flush();
    }

    /**
     * @param $submissionId
     * @param $response
     * @param $status
     *
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    private function updateSubmissionByStatus($submissionId, $response, $status)
    {
        $em = self::getEntityManager();
        /** @var Submission $submission */
        $submission = $em->find(Submission::class, $submissionId);

        $submission->setResponse($response);
        $submission->setStatus($status);
        $submission->setSendDate(Carbon::now());

        self::getEntityManager()->flush();

        return true;
    }

    /**
     * @param Criteria $criteria
     * @return \Doctrine\ORM\QueryBuilder
     * @throws \Doctrine\ORM\Query\QueryException
     */
    protected function fetchSubmissionsByCriteria(Criteria $criteria): \Doctrine\ORM\QueryBuilder
    {
        $em = self::getEntityManager();

        $query = $em->getRepository(Submission::class)->createQueryBuilder("s")
            ->innerJoin(Account::class, "a", 'WITH', 'a.accountId = s.account')
            ->innerJoin(\App\Entity\Profile::class, "p", 'WITH', 'p.account = s.account')
            ->innerJoin(\App\Entity\Marketing\CoregPlugin::class, "cp", 'WITH', 's.coregPlugin = cp.id')
            ->orderBy("s.submissionId", "desc")
            ->addCriteria($criteria);
        return $query;
    }
}