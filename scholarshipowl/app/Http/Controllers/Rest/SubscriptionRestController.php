<?php namespace App\Http\Controllers\Rest;

use App\Entity\Account;
use App\Entity\MarketingSystemAccountData;
use App\Entity\Repository\SubscriptionRepository;
use App\Entity\Resource\SubscriptionResource;
use App\Entity\Subscription;
use App\Http\Controllers\RestController;
use App\Rest\Filter\MarketingDataFilter;
use App\Rest\Index\SimpleFiltersQueryBuilder;
use App\Services\PaymentManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use ScholarshipOwl\Data\DateHelper;
use ScholarshipOwl\Doctrine\ORM\QueryIterator;
use Doctrine\ORM\Query\Expr\Join;

class SubscriptionRestController extends RestController
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var SubscriptionRepository
     */
    protected $repository;

    /**
     * SubscriptionRestController constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
        $this->repository = $em->getRepository(Subscription::class);
    }

    /**
     * @return SubscriptionRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return SubscriptionResource
     */
    public function getResource()
    {
        return new SubscriptionResource();
    }

    /**
     * @param Request $request
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getBaseIndexQuery(Request $request)
    {
        return $this->getRepository()->createQueryBuilder('subscription')
            ->addSelect([
                'CONCAT_WS(\' \', p.firstName, p.lastName) AS accountName',
                'md.value AS affiliateId'
            ])
            ->join('subscription.account', 'a')
            ->join('a.profile', 'p')
            ->innerJoin(Account::class, 'acc', Join::WITH, 'acc.accountId = a.accountId')
            ->leftJoin('a.marketingData', 'md', Join::WITH, 'md.account = a.accountId AND md.name = \'affiliate_id\'');
    }

    /**
     * @param Request $request
     *
     * @return $this|\ScholarshipOwl\Doctrine\ORM\QueryBuilderChain
     */
    public function getBaseIndexQueryBuilderChain(Request $request)
    {
        return parent::getBaseIndexQueryBuilderChain($request)
            ->add(new MarketingDataFilter($request));
    }

    /**
     * @param Request $request
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getBaseIndexCountQuery(Request $request)
    {
        return $this->getBaseIndexQuery($request)
        ->resetDQLPart('join')
        ->innerJoin(Account::class, 'acc', Join::WITH, 'acc.accountId = subscription')
        ->select('COUNT(subscription.subscriptionId)');
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function getIndexAliasesJoins(Request $request) : array
    {
        return parent::getIndexAliasesJoins($request) + [
            'account' => function(QueryBuilder $qb) {
                return $qb->join('subscription.account', 'account');
            },
            'profile' => function(QueryBuilder $qb) {
                return $qb->join('subscription.account', 'p_account')
                    ->join('p_account.profile', 'profile');
            },
            'package' => function(QueryBuilder $qb) {
                return $qb->join('subscription.package', 'package');
            },
            'transaction' => function(QueryBuilder $qb) { return $qb; },
        ];
    }

    public function getIndexCountQueries(Request $request) : array
    {
        return parent::getIndexCountQueries($request) + [
            'transaction.transactionId' => function(QueryBuilder $qb, string $operator, string $param) {
                $qb->andWhere(
                    $qb->expr()->in(
                        'subscription.subscriptionId',
                        $qb->getEntityManager()->createQueryBuilder()
                            ->from(Subscription::class, 's')
                            ->select('DISTINCT s.subscriptionId')
                            ->leftJoin('s.transactions', 't')
                            ->groupBy('s.subscriptionId')
                            ->having(SimpleFiltersQueryBuilder::operatorExpr(
                                $qb->expr()->countDistinct('t.transactionId'),
                                $operator,
                                $param
                            ))
                            ->getQuery()
                            ->getDQL()
                    )
                );
            }
        ];
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        $this->authorize('export', $this->getRepository()->getClassName());

        $query = $this->getBaseIndexQueryBuilderChain($request)
            ->process($this->getBaseIndexQuery($request))
            ->select(['subscription', 'a', 'p', 'amd'])
            ->leftJoin('a.marketingData', 'amd')
            ->getQuery();

        $name = storage_path(sprintf('framework/cache/Subscription-Export-%s.csv', date('Y-m-d')));
        $writer = \CsvWriter::create($name);
        $writer->writeLine([
            'Id', 'Start', 'Status', 'R.Status', 'Account Id', 'Account Name', 'Package',
            'Price', 'Renewal', 'Free Trail', 'End', 'Terminated At'
        ]);

        /**
         * Iterate in chunks over items and write to CSV file.
         */
        foreach(QueryIterator::create($query, 10000) as $items) {
            $writer->writeAll(array_map(
                function(Subscription $subscription) {
                    return [
                        $subscription->getSubscriptionId(),
                        $subscription->getStartDate()->format(DateHelper::DEFAULT_FORMAT),
                        $subscription->getSubscriptionStatus()->getName(),
                        $subscription->getRemoteStatus(),
                        $subscription->getAccount()->getAccountId(),
                        $subscription->getAccount()->getProfile()->getFullName(),
                        $subscription->getName(),
                        $subscription->getPrice(),
                        $subscription->getRenewalDate()->format(DateHelper::DEFAULT_FORMAT),
                        $subscription->isFreeTrial() ? 'Yes' : 'No',
                        $subscription->getEndDate()->format(DateHelper::DEFAULT_FORMAT),
                        $subscription->getTerminatedAt() ?
                            $subscription->getTerminatedAt()->format(DateHelper::DEFAULT_FORMAT) : '0000-00-00 00:00:00',
                        MarketingSystemAccountData::getAffiliateId($subscription->getAccount()),
                    ];
                },
                $items
            ));
        }

        $writer->close();

        return \Response::download($name);
    }


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function cancelSubscription($id)
    {
        /** @var Subscription $subscription */
        $subscription = $this->em->find(Subscription::class, $id);
        if (!$subscription) {
            return $this->jsonErrorResponse('Subscription not found', JsonResponse::HTTP_NOT_FOUND);
        }

        $this->authorize('cancel', $subscription);

        /** @var PaymentManager $pm */
        $pm = app(PaymentManager::class);
        $pm->cancelSubscription($subscription);

        return $this->jsonSuccessResponse([]);
    }
}
