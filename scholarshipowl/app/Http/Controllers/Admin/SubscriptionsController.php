<?php namespace App\Http\Controllers\Admin;

use App\Entity\Account;
use App\Entity\Package;
use App\Entity\Repository\AccountRepository;
use App\Entity\Repository\EntityRepository;
use App\Entity\SubscriptionStatus;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

class SubscriptionsController extends BaseController
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $packagesRepository;

    /**
     * @var AccountRepository
     */
    protected $accounts;

    /**
     * SubscriptionsController constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct();

        $this->em = $em;
        $this->packagesRepository = $em->getRepository(Package::class);
        $this->accounts = $em->getRepository(Account::class);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function indexAction()
    {
        $data = ['packages' => []];

        /** @var Package $package */
        foreach ($this->packagesRepository->findAll() as $package) {
            $data['packages'][$package->getPackageId()] =
                sprintf('%s (%s)', $package->getName(), $package->getPackageId());
        }

        return $this->view('Subscriptions - Grid', 'admin.subscriptions.index', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function doubleSubscriptions()
    {
        $data = $this->accounts->createQueryBuilder('a')
            ->select(['a.accountId AS id', 'a.email', 'COUNT(s.subscriptionId) AS scount'])
            ->leftJoin('a.subscriptions', 's')
            ->andWhere('s.price > 0 AND s.subscriptionStatus = :activeStatus')
            ->setParameter('activeStatus', SubscriptionStatus::ACTIVE)
            ->groupBy('a.accountId')
            ->having('scount > 1')
            ->getQuery()
            ->getResult();

        return $this->view('Subscriptions - Grid', 'admin.subscriptions.doubleSubscriptions', [
            'data' => $data
        ]);
    }
}
