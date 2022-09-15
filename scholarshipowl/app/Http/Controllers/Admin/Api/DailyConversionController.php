<?php namespace App\Http\Controllers\Admin\Api;

use App\Entity\Account;
use App\Entity\Subscription;
use App\Entity\SubscriptionAcquiredType;
use App\Entity\Transaction;
use App\Entity\TransactionStatus;
use App\Http\Controllers\Admin\BaseController;
use App\Http\Traits\JsonResponses;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use Illuminate\Http\Request;
use DateTime;
use ScholarshipOwl\Data\DateHelper;

class DailyConversionController extends BaseController
{
    use JsonResponses;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * DailyConversionController constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function conversion(Request $request)
    {
        $start = new DateTime($request->get('start', '-1 month'));
        $end = new DateTime($request->get('end', 'now'));

        $registeredAccounts = $this->baseQuery($start, $end, 'registered')
            ->getQuery()->getArrayResult();

        $subscriptions = $this->baseQuery($start, $end, 'subscription')
            ->join(Subscription::class, 's', Join::WITH, 'a.accountId = s.account')
            ->andWhere('s.startDate >= :start AND s.startDate < :end')
            ->andWhere('s.subscriptionAcquiredType = :purchased')
            ->setParameter('purchased', SubscriptionAcquiredType::PURCHASED)
            ->getQuery()->getArrayResult();

        $new = $this->baseQuery($start, $end, 'new_transactions')
            ->join(Transaction::class, 't', Join::WITH, 'a.accountId = t.account')
            ->andWhere('DATE(t.createdDate) = DATE(a.createdDate) AND t.transactionStatus = :success')
            ->setParameter('success', TransactionStatus::SUCCESS)
            ->getQuery()->getArrayResult();

        $transactions = $this->transactions($start, $end, 'transaction');

        $result = $this->mergeByDate($registeredAccounts);
        $result = $this->defaultValues($this->mergeByDate($new, $result), 'new_transactions');
        $result = $this->defaultValues($this->mergeByDate($transactions, $result), 'transaction');
        $result = $this->defaultValues($this->mergeByDate($subscriptions, $result), 'subscription');
        $result = $this->conversionRate($result);

        return $this->jsonSuccessResponse($result, [
            'start' => $start->format('Y-m-d'),
            'end' => $end->format('Y-m-d')
        ]);
    }

    /**
     * @param DateTime $start
     * @param DateTime $end
     * @param string   $alias
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function baseQuery(\DateTime $start, \DateTime $end, string $alias)
    {
        return $this->em->createQueryBuilder()
            ->select(['DATE(a.createdDate) AS date', sprintf('COUNT(DISTINCT a.accountId) AS %s', $alias)])
            ->from(Account::class, 'a')
            ->where('a.createdDate >= :start AND a.createdDate < :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->groupBy('date');
    }

    /**
     * @param DateTime $start
     * @param DateTime $end
     * @param string   $alias
     *
     * @return array
     */
    protected function transactions(\DateTime $start, \DateTime $end, string $alias)
    {
        return $this->em->createQueryBuilder()
            ->select(['DATE(t.createdDate) AS date', sprintf('COUNT(DISTINCT t.transactionId) AS %s', $alias)])
            ->from(Transaction::class, 't')
            ->leftJoin('t.subscription', 's')
            ->where('t.createdDate >= :start AND t.createdDate < :end AND t.transactionStatus = :success')
            ->andWhere('s.recurrentCount IS NULL OR s.recurrentCount = 1')
            ->setParameter('success', TransactionStatus::SUCCESS)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->groupBy('date')
            ->getQuery()->getResult();
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function conversionRate(array $data) : array
    {
        foreach ($data as $date => $value) {
            $data[$date]['conversion'] = round($value['new_transactions'] / ($value['registered']/100), 2);
            $data[$date]['conversion_t'] = round($value['transaction'] / ($value['registered']/100), 2);
            $data[$date]['conversion_s'] = round($value['subscription'] / ($value['registered']/100), 2);
        }

        return $data;
    }

    /**
     * @param array  $data
     * @param array  $mergeTo
     * @param string $key
     *
     * @return array
     */
    protected function mergeByDate(array $data, array $mergeTo = [], string $key = 'date') : array
    {
        foreach ($data as $row) {
            if (isset($row[$key]) && $date = $row[$key]) {
                unset($row[$key]);
                $mergeTo[$date] = isset($mergeTo[$date]) ? $mergeTo[$date] + $row : $row;
            }
        }

        return $mergeTo;
    }

    /**
     * @param array  $data
     * @param string $key
     * @param int    $default
     *
     * @return array
     */
    protected function defaultValues(array $data, string $key, $default = 0) : array
    {
        foreach ($data as $index => $value) {
            if (!isset($value[$key])) {
                $data[$index][$key] = $default;
            }
        }

        return $data;
    }
}
