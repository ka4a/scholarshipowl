<?php namespace App\Http\Middleware;

use App\Entity\Account;
use App\Entity\FeatureAbTest;
use App\Entity\FeatureSet;
use App\Entity\Repository\FeatureAbTestRepository;
use Closure;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FeatureAbTestsMiddleware
{
    const PARAM_AB_TEST = 'DEBUG_FSET';

    const PARAM_HO_PARAM = 'fset';

    const COOKIE_FEATURE_SET = '_sofset';

    const FEATURE_SET_CACHE_KEY = "feature-set-%s";
    const FEATURE_SET_CACHE_TAG = "feature-set-tag";

    const FEATURE_SET_CACHE_TTL = 24 * 60 * 60;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var FeatureAbTestRepository
     */
    protected $repository;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $sets;

    /**
     * FeatureAbTestsMiddleware constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(FeatureAbTest::class);
        $this->sets = $em->getRepository(FeatureSet::class);
    }

    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return $this
     */
    public function handle($request, Closure $next)
    {
        if (is_testing()) {
            return $next($request);
        }

        if ($name = $request->get(static::PARAM_AB_TEST)) {
            if ($set = $this->findFeatureSetByName($name)) {
                FeatureSet::set($set);
                return $next($request);
            }
        }

        $set = $this->getPriotityFset($request);
        if(!is_null($set)){
            FeatureSet::set($set);
        }

        if (!$set) {
            /** @var FeatureAbTest $test */
            foreach ($this->repository->findByActive() as $test) {
                $config = $test->getConfig();

                if (isset($config['percentage']) && mt_rand(1, 100) <= $config['percentage']) {
                    FeatureSet::set($test->getFeatureSet());
                    break;
                }
            }
        }

        $response = $next($request);
        if ($response instanceof Response) {
            if (is_null(\Auth::user())) {
                $response->withCookie($this->cookie(FeatureSet::config()));
            } else {
                $this->setAccountFset(FeatureSet::config());
            }
        }

        //handling auth with new fset
        if($response instanceof JsonResponse && $this->getAccountFset()) {
            $this->setAccountFset(FeatureSet::config());
        }

        return $response;
    }

    /**
     * @param $name
     *
     * @return FeatureSet|null
     */
    protected function findFeatureSetByName($name)
    {
        if (!$name) {
            return null;
        }

        $key = sprintf(self::FEATURE_SET_CACHE_KEY, $name);

        if (\Cache::tags([self::FEATURE_SET_CACHE_TAG])->has($key)) {
            return \Cache::tags([self::FEATURE_SET_CACHE_TAG])->get($key);
        }

        $result = $this->sets->findOneBy(['name' => $name, 'deleted' => false]);
        \Cache::tags([self::FEATURE_SET_CACHE_TAG])->put($key, $result, self::FEATURE_SET_CACHE_TTL);

        return $result;
    }

    /**
     * @param int|FeatureSet $set
     *
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    protected function cookie($set)
    {
        return cookie(static::COOKIE_FEATURE_SET, FeatureSet::convert($set)->getName(), 60 * 60 * 24 * 365, '/');
    }

    /**
     * @param $request
     *
     * @return FeatureSet
     */
    protected function getPriotityFset($request)
    {
        $set = null;

        $hoFsetParam = $request->get(static::PARAM_HO_PARAM);
        $cookieFsetParam = $request->cookie(static::COOKIE_FEATURE_SET);

        $priotityFset = 'hoFset';
        $fsetInRequest = [];
        $fsetInRequest['hoFset'] = $this->findFeatureSetByName($hoFsetParam);
        $fsetInRequest['cookieFset']
            = $this->findFeatureSetByName($cookieFsetParam);

        $fsetInRequest['accountFset'] = null;

        if ($this->getAccountFset()) {
            $fsetInRequest['accountFset'] = $this->getAccountFset();
        }

        //Reduce fsets array to one priority fset
        $setIndex = array_reduce(array_keys($fsetInRequest),
            function ($prev, $item) use ($fsetInRequest, $priotityFset) {
                if ($prev != $priotityFset && !is_null($fsetInRequest[$item])) {
                    return $item;
                } elseif ($item == $priotityFset
                    && !is_null($fsetInRequest[$item])
                ) {
                    return $item;
                } else {
                    return $prev;
                }
            });

        if(!is_null($setIndex)){
            $set = $fsetInRequest[$setIndex];
        }

        return $set;
    }

    protected function getAccountFset()
    {
        $fset = false;
        if (\Auth::user() instanceof Account) {
            /**
             * @var Account $account
             */
            $account = \Auth::user();
            $fset = $account->getFset();
        }

        return $fset;
    }

    protected function setAccountFset($fset)
    {
        if (\Auth::user() instanceof Account) {
            /**
             * @var Account $account
             */
            $account = \Auth::user();
            $accountFset = $account->getFset();

            if(is_null($accountFset) || $accountFset->getId() != $fset->getId()) {
                $reference = \EntityManager::getReference(FeatureSet::class, $fset->getId());
                $account->setFset($reference);
                \EntityManager::persist($account);
                \EntityManager::flush($account);
            }
        }
    }
}
