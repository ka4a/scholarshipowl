<?php

namespace ScholarshipOwl\Domain\Payment\Gate2Shop;

use ScholarshipOwl\Data\Entity\Account\Account;
use ScholarshipOwl\Data\Entity\Info\Country;
use ScholarshipOwl\Data\Entity\Info\State;
use ScholarshipOwl\Data\Entity\Payment\Package;
use ScholarshipOwl\Data\Service\Info\InfoServiceFactory;
use ScholarshipOwl\Domain\Log\GTSPaymentFormUrl;

class UrlBuilder
{

    /**
     * @var Package
     */
    protected $package;

    /**
     * @var Account
     */
    protected $account;

    /**
     * @var string
     */
    protected $trackingParams;

    public function __construct(Package $package = null, Account $account = null, $trackingParams = null)
    {
        if ($package !== null) {
            $this->setPackage($package);
        }
        if ($account !== null) {
            $this->setAccount($account);
        }
        if ($trackingParams !== null) {
            $this->setTrackingParams($trackingParams);
        }
    }

    /**
     * @return Package
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * @param Package $package
     * @return $this
     */
    public function setPackage(Package $package)
    {
        $this->package = $package;

        return $this;
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param Account $account
     * @return $this
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return string
     */
    public function getTrackingParams()
    {
        return $this->trackingParams;
    }

    /**
     * @param $trackingParams
     * @return $this
     */
    public function setTrackingParams($trackingParams)
    {
        $this->trackingParams = $trackingParams;

        return $this;
    }

    public function getPaymentUrl()
    {
        $config = Helper::getConfig();

        if (null === ($package = $this->getPackage())) {
            throw new Exception("Missing package for build URL");
        }

        if (null === ($account = $this->getAccount())) {
            throw new Exception("Missing account for build URL");
        }

        if (null === ($trackingParams = $this->getTrackingParams())) {
            throw new Exception("Missing tracking params for build URL");
        }

        $data = $this->getDefaultData($config)
            + $this->getPackageData()
            + $this->getUserData()
            + $this->getCustomData();

		$data["checksum"] = $this->getChecksum($data, $config);

		$url = $config["process_url"] . "?" . http_build_query($data);

        GTSPaymentFormUrl::logFormUrl($this->getAccount()->getAccountId(), $url);
		return $url;
    }

    protected function getChecksum($data, $config)
    {
        if ($this->getPackage()->getExpirationType() === Package::EXPIRATION_TYPE_RECURRENT) {

            $checksum = md5(implode('', array(
                $config['secret_key'],
                $config['merchant_id'],
                $data['rebillingProductId'],
                $data['rebillingTemplateId'],
                $data['time_stamp'],
            )));

        } else {

            $checksum = md5(implode('', array(
                $config["secret_key"],
                $data["merchant_id"],
                "USD",
                $data["total_amount"],
                $data["item_name_1"],
                $data["item_amount_1"],
                $data["item_quantity_1"],
                $data['time_stamp']
            )));

        }

        return $checksum;
    }

    protected function getDefaultData(array $config)
    {
        return array(
            "version" => "3.0.0",
            "merchant_id" => $config["merchant_id"],
            "merchant_site_id" => $config["merchant_site_id"],
            "time_stamp" => date("Y-m-d H:i:s"),
        );
    }

    protected function getCustomData()
    {
        return array(
            "customField1" => $this->getPackage()->getPackageId(),
            "customField2" => $this->getAccount()->getAccountId(),
            "customField3" => $this->getTrackingParams(),
        );
    }

    protected function getPackageData()
    {
        $package = $this->getPackage();

        if ($package->getExpirationType() === Package::EXPIRATION_TYPE_RECURRENT) {

            $data = array(
                'isRebilling' => 'true',

                'rebillingProductId' => $package->getG2SProductId(),
                'rebillingTemplateId' => $package->getG2STemplateId(),

                'initial_amount' => $package->getPrice(),
                'rebilling_amount' => $package->getPrice(),
                'rebilling_currency' => 'USD',
            );

        } else {

            $data = array(
                "currency" => "USD",
                "total_amount" => $package->getPrice(),

                "item_name_1" => $package->getName(),
                "item_amount_1" => $package->getPrice(),
                "item_quantity_1" => 1,
            );

        }

        return $data;
    }

	protected function getUserData()
    {
        $account = $this->getAccount();
		$country = $account->getProfile()->getCountry();
		$state = $account->getProfile()->getState();
		$countryAbbreviation = "";
		$stateAbbreviation = "";

		if($country->getCountryId()) {
            /** @var Country $country */
			$country = InfoServiceFactory::get("Country")->getById($country->getCountryId(), true);
			$countryAbbreviation = $country->getAbbreviation();
		}

		if($state->getStateId()) {
            /** @var State $state */
			$state = InfoServiceFactory::get("State")->getById($state->getStateId(), true);
			$stateAbbreviation = $state->getAbbreviation();
		}

		$result = array(
			"first_name" => $account->getProfile()->getFirstName(),
			"last_name" => $account->getProfile()->getLastName(),
			"email" => $account->getEmail(),
			"country" => $countryAbbreviation,
			"state" => $stateAbbreviation,
			"address1" => $account->getProfile()->getAddress(),
			"city" => $account->getProfile()->getCity(),
			"zip" => $account->getProfile()->getZip(),
			"phone1" => $account->getProfile()->getPhone(),

            "cc_name_on_card" => $account->getProfile()->getFullName()
		);

        $result['address1'] = (strlen($result['address1']) > 60) ?
            substr($result['address1'], 0, 57) . "..." : $result['address1'];

		return $result;
	}

}