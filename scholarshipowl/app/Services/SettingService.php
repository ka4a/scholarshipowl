<?php namespace App\Services;

use App\Entity\Account;
use App\Entity\Country;
use App\Entity\FeaturePaymentSet;
use App\Entity\FeatureSet;
use App\Entity\OnesignalAccount;
use App\Entity\Repository\EntityRepository;
use App\Entity\Repository\OnesignalAccountRepository;
use App\Entity\Setting;
use Braintree\Configuration;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Illuminate\Cache\CacheManager;

class SettingService
{
    const CACHE_KEY = 'cache-setting-key';
    const CACHE_NAMES_KEY = 'cache-setting-names';
    const CACHE_TTL = 7 * 24 * 60 * 60;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var ArrayCollection|Setting[]
     */
    protected $settings;

    /**
     * @var ArrayCollection|Setting[]
     */
    protected $settingNames;

    /**
     * Setting constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
        $this->repository = $entityManager->getRepository(Setting::class);
    }

    /**
     * @return ArrayCollection|Setting[]
     */
    protected function all()
    {
        if ($this->settings === null) {
            if (null === ($this->settings = \Cache::get(static::CACHE_KEY))) {
                $settings = $this->repository->createQueryBuilder('s')
                    ->select(['s.name', 's.value'])
                    ->getQuery()
                    ->getScalarResult();

                $this->settings = new ArrayCollection();
                foreach ($settings as $setting) {
                    $this->settings[$setting['name']] = @json_decode($setting['value'], true) ?? $setting['value'];
                }

                \Cache::put(static::CACHE_KEY, $this->settings, static::CACHE_TTL);
            }
        }

        return $this->settings;
    }

    /**
     * @return string
     */
    public function json()
    {
        return json_encode($this->jsonData());
    }

    /**
     * @return array
     */
    public function jsonData()
    {
        $featureSet = FeatureSet::config();

        $data = [
            'fset'                 => ['id' => $featureSet->getId(), 'name' => $featureSet->getName()],
            'onesignal.app'         => config('onesignal.web.app_id'),
            'defaultPaymentMethod'  => FeaturePaymentSet::config()->getPaymentMethod()->getId(),
            'btEnv'  => empty(Configuration::environment()) ? 'production' : Configuration::environment(),
            'uc'                    => \Auth::user() ?
                \Auth::user()->getProfile()->getCountry()->getAbbreviation() :
                Country::getCountryCodeByIP(),
            'content' => [
                'showPhone' => $this->get('content.phone.show') === "yes" ? 1 : 0,
                'phoneNumber' => $this->get('content.phone')
            ],
            'scholarships' => [
                'mobile_app_ad' => $this->get('scholarships.mobile_app_ad') === "yes" ? 1 : 0
            ]
        ];

        return $data;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function get(string $name)
    {
        if (!isset($this->all()[$name])) {
            $this->refresh();

            if (!isset($this->all()[$name])) {
                throw new \InvalidArgumentException(sprintf('Setting %s not found!', $name));
            }
        }

        return $this->all()[$name];
    }

    /**
     * Flush cache and retrieve from DB
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function refresh()
    {
        \Cache::delete(static::CACHE_KEY);
        $this->settings = null;
        $this->all();
    }

    /**
     * @param string      $name
     * @param mixed|null  $default
     *
     * @return string
     */
    public function value(string $name, $default = null)
    {
        if ($setting = $this->get($name)) {
            return $setting->getValue();
        }

        return $default;
    }

    /**
     * @param string $name
     * @param        $value
     *
     * @return $this
     */
    public function set(string $name, $value)
    {
        \EntityManager::flush($this->get($name)->setValue($value));
        $this->refresh();

        return $this;
    }

    /**
     * @param string $type
     * @param string $group
     * @param string $name
     * @param string $title
     * @param string $value
     * @param string $defaultValue
     * @param string $options
     *
     * @return Setting
     */
    public function create(
        string $type,
        string $group,
        string $name,
        string $title,
        string $value = '',
        string $defaultValue = '',
        $options = ''
    )
    {
        $setting = new Setting($type, $group, $name, $title, $value, $defaultValue, $options);

        $this->em->persist($setting);
        $this->em->flush($setting);
        \Cache::delete(static::CACHE_KEY);

        return $setting;
    }

    /**
     * @param string $name
     * @param string $group
     * @param string $title
     * @param bool   $value
     *
     * @return Setting
     */
    public function createBoolean(
        string $name,
        string $group,
        string $title,
        bool   $value = false
    ) {
        return $this->create(
            Setting::TYPE_SELECT, $group, $name, $title, $value ? 'yes' : 'no', 'no', ['yes' => 'Yes', 'no' => 'No']
        );
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function delete(string $name)
    {
        $setting = $this->repository->findOneBy(['name' => $name]);

        $this->em->remove($setting);
        $this->em->flush();
        $this->refresh();

        return $this;
    }

    public function getAvailableSettingsInRest()
    {
        if ($this->settingNames === null) {
            if (null === ($this->settingNames = \Cache::get(static::CACHE_NAMES_KEY))) {
                $settings = $this->repository->createQueryBuilder('s')
                    ->select(['s.name'])
                    ->where('s.isAvailableInRest = 1')
                    ->getQuery()->getResult();

                $this->settings = new ArrayCollection();
                foreach ($settings as $setting) {
                    $this->settingNames[] = $setting['name'];
                }

                \Cache::put(static::CACHE_NAMES_KEY, $this->settingNames, static::CACHE_TTL);
            }
        }

        return $this->settingNames;
    }
}
