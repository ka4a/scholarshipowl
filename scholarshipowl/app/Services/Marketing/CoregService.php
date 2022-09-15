<?php


namespace App\Services\Marketing;


use App\Entity\Account;
use App\Entity\Country;
use App\Entity\Marketing\CoregPlugin;
use App\Entity\Marketing\CoregPluginAllocation;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Illuminate\Cache\TaggedCache;
use Illuminate\Http\Request;

class CoregService
{
    const CACHE_KEY = 'coreg';
    const CACHE_TAGS = ['service.coreg'];
    const CACHE_TTL = 30;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var array
     */
    private $positions = [
        "register" => [
            "coreg1",
            "coreg2",
            "coreg3",
            "coreg1a",
            "coreg2a",
            "coreg3a",
        ],
        "register2" => [
            "coreg4"
        ],
        "register3" => [
            "coreg5",
            "coreg6",
            "coreg5a",
            "coreg6a"
        ]
    ];


    /**
     * @var CoregRequirementsRuleService
     */
    protected $requirementsRuleService;

    public function __construct(CoregRequirementsRuleService $requirementsRuleService, EntityManager $em)
    {
        $this->requirementsRuleService = $requirementsRuleService;
        $this->em = $em;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getRepository()
    {
        return $this->em->getRepository(CoregPlugin::class);
    }

    /**
     * @param string $coregPluginId
     * @return CoregPlugin
     */
    public function getCoregPlugin($coregPluginId)
    {
        return $this->getRepository()->find($coregPluginId);
    }

    /**
     * @param string $position
     * @param Account $account
     *
     * @return ArrayCollection|CoregPlugin[]
     */
    public function getCoregPluginsByPosition($position = null, $account = null)
    {
        /**
         * Not coregs for non-usa.
         */
        if (!($account ? $account->isUSA() : Country::getCountryCodeByIP() === 'US')) {
            return new ArrayCollection();
        }

        /** @var TaggedCache $cache */
        $position = $position ?: 'register';

        $coregs = $this->getRepository()->findBy([
            'displayPosition' => $this->positions[$position ?: "register"],
            'isVisible'       => true
        ]);

        if ($account) {
            $coregs = $this->requirementsRuleService->checkUserAgainstAllRuleList($coregs, $account->getAccountId());
        }

        return new ArrayCollection($coregs);
    }

    /**
     * @param string $name
     * @return CoregPlugin
     */
    public function getCoregPluginByName($name)
    {
        return $this->getRepository()->findOneBy(["name" => $name]);
    }

    public function updateCoregPluginAllocation($coregPlugin, $type = "month")
    {
        if ($type == "day") {
            $date = Carbon::now()->startOfDay();
        } else {
            $date = new Carbon("first day of this month");
        }

        /** var CoregAllocation $coregAllocation */
        if (!$coregAllocation = $this->em->getRepository(CoregPluginAllocation::class)->findOneBy([
            "coregPlugin" => $coregPlugin,
            "type" => $type,
            "date" => $date
        ])
        ) {
            $coregAllocation = new CoregPluginAllocation();
            $coregAllocation->setCoregPlugin($coregPlugin);
            $coregAllocation->setType($type);
            $coregAllocation->setDate($date);
            $this->em->persist($coregAllocation);
        }else{
            $coregAllocation->setCount($coregAllocation->getCount() + 1);
        }

        $this->em->flush();
    }

    public function getRemainingPluginCap($coregPlugin, $type = "month")
    {
        if ($coregPlugin->getMonthlyCap() == 0 || $coregPlugin->getMonthlyCap() == null) {
            return false;
        }

        if ($type == "day") {
            $date = Carbon::now()->startOfDay();
        } else {
            $date = new Carbon("first day of this month");
        }

        $coregAllocation = $this->em->getRepository(CoregPluginAllocation::class)->findOneBy([
            "coregPlugin" => $coregPlugin,
            "type" => $type,
            "date" => $date
        ]);

        if (!$coregAllocation) {
            return $coregPlugin->getMonthlyCap();
        } else {
            return max($coregPlugin->getMonthlyCap() - $coregAllocation->getCount(), 0);
        }
    }

    /**
     * @param Request $request
     * @param Account $account
     *
     * @return ArrayCollection|\Doctrine\Common\Collections\Collection
     */
    public function getCoregsByRequest(Request $request, Account $account = null)
    {
        $path = 'register';

        if (strpos($request->path(), 'register2') !== false) {
            $path = 'register2';
        }

        if (strpos($request->path(), 'register3') !== false) {
            $path = 'register3';
        }

        return $this->getCoregsByPath($path, $account);
    }

    /**
     * @param string      $path
     * @param Account|int $account
     *
     * @return CoregPlugin[]|ArrayCollection
     * @throws \Doctrine\ORM\ORMException
     */
    public function getCoregsByPath($path, $account = null)
    {
        if (!isset($this->positions[$path])) {
            throw new \InvalidArgumentException(
                'Invalid path parameter. Coregs position setting doesn\'t has this path.'
            );
        }

        if ($account && !$account instanceof Account) {
            $account = $this->em->getReference(Account::class, $account);
        }

        return $this->getCoregPluginsByPosition($path, $account);
    }
}
