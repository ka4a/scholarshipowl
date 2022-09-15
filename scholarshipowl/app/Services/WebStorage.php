<?php namespace App\Services;

use App\Entity\Account;
use App\Entity\FeatureCompanyDetailsSet;
use App\Entity\FeaturePaymentSet;
use App\Entity\FeatureSet;
use App\Entity\Resource\AccountResource;
use App\Entity\Resource\CoregsResource;
use App\Entity\Resource\FeatureSet\FeatureCompanyDetailsSetResource;
use App\Entity\Resource\FeatureSetResource;
use App\Services\Marketing\CoregService;
use ScholarshipOwl\Data\ResourceCollection;

class WebStorage
{
    /**
     * @var array
     */
    protected $storage = [];

    /**
     * @var OptionsManager
     */
    protected $options;

    /**
     * @var SettingService
     */
    protected $settings;

    /**
     * @var CoregService
     */

    protected $cs;
    /**
     * WebStorage constructor.
     *
     * @param OptionsManager $options
     */
    public function __construct(OptionsManager $options, SettingService $settings, CoregService $cs)
    {
        $this->options = $options;
        $this->settings = $settings;
        $this->cs = $cs;
    }

    /**
     * @return string
     */
    public function json()
    {
        /** @var Account|null $account */
        $account = \Auth::user() instanceof Account ? \Auth::user() : null;
        $coregs = $this->cs->getCoregsByRequest(request(), $account);

        $companyDetailsSet = FeatureCompanyDetailsSet::config();
        $default = [
            'settings'  => $this->settings->jsonData(),
            'account'   => $account ? AccountResource::entityToArray($account) : null,
            'fset'      => FeatureSetResource::entityToArray(FeatureSet::config()),
            'coregs'    => ResourceCollection::collectionToArray(new CoregsResource(), $coregs),
            'companyDetails' => $companyDetailsSet ? \App\Entity\Resource\FeatureSet\FeatureCompanyDetailsSetResource::entityToArray($companyDetailsSet) : null
        ];

        return json_encode($default + $this->storage);
    }

    /**
     * New SOWLStorage without redundant data, which is not needed on every single request
     *
     * @return string
     */
    public function jsonOptimized()
    {
        $companyDetailsSet = FeatureCompanyDetailsSet::config();
        $default = [
            'settings' => $this->settings->jsonData(),
            'fset' => FeatureSetResource::entityToArray(FeatureSet::config()),
            'companyDetails' => $companyDetailsSet ? \App\Entity\Resource\FeatureSet\FeatureCompanyDetailsSetResource::entityToArray($companyDetailsSet) : null
        ];

        return json_encode($default + $this->storage);
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->storage[$key] = $value;
    }
}
