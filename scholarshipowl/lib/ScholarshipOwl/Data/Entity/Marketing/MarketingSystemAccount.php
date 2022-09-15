<?php

namespace ScholarshipOwl\Data\Entity\Marketing;

use App\Entity\Repository\AccountRepository;
use ScholarshipOwl\Data\Entity\AbstractEntity;
use App\Entity\Account;


class MarketingSystemAccount extends AbstractEntity {
    const HAS_OFFERS_URL_PARAM_AFF_ID = "aff_id";
    const HAS_OFFERS_URL_PARAM_AFFILIATE_ID = "affiliate_id";
    const HAS_OFFERS_URL_PARAM_AFFILIATE_NAME = "affiliate_name";
    const HAS_OFFERS_URL_PARAM_SOURCE = "source";
    const HAS_OFFERS_URL_PARAM_AFF_SUB = "aff_sub";
    const HAS_OFFERS_URL_PARAM_AFF_SUB2 = "aff_sub2";
    const HAS_OFFERS_URL_PARAM_AFF_SUB3 = "aff_sub3";
    const HAS_OFFERS_URL_PARAM_AFF_SUB4 = "aff_sub4";
    const HAS_OFFERS_URL_PARAM_AFF_SUB5 = "aff_sub5";
    const HAS_OFFERS_URL_PARAM_OFFER_ID = "offer_id";
    const HAS_OFFERS_URL_PARAM_OFFER_NAME = "offer_name";
    const HAS_OFFERS_URL_PARAM_OFFER_URL_ID = "offer_url_id";
    const HAS_OFFERS_URL_PARAM_OFFER_FILE_ID = "offer_file_id";
    const HAS_OFFERS_URL_PARAM_FILE_NAME = "file_name";
    const HAS_OFFERS_URL_PARAM_ADVERTISER_ID = "advertiser_id";
    const HAS_OFFERS_URL_PARAM_COUNTRY_CODE = "country_code";
    const HAS_OFFERS_URL_PARAM_REGION_CODE = "region_code";
    const HAS_OFFERS_URL_PARAM_PARAMS = "params";
    const HAS_OFFERS_URL_PARAM_TRANSACTION_ID = "transaction_id";
    const HAS_OFFERS_URL_PARAM_DATE = "date";
    const HAS_OFFERS_URL_PARAM_TIME = "time";
    const HAS_OFFERS_URL_PARAM_DATETIME = "datetime";
    const HAS_OFFERS_URL_PARAM_IP = "ip";
    const HAS_OFFERS_URL_PARAM_OFFER_REF = "offer_ref";
    const HAS_OFFERS_URL_PARAM_AFFILIATE_REF = "affiliate_ref";
    const HAS_OFFERS_URL_PARAM_ADVERTISER_REF = "advertiser_ref";

    /**
     * @var Account
     */
    private $account;

    /**
     * @var int
     */
    private $accountId;

    private $marketingSystem;
    private $conversionDate;
    private $data;
    
    
    public function __construct() {
        $this->marketingSystem = new MarketingSystem();
        $this->conversionDate = "0000-00-00 00:00:00";
        $this->data = array();
    }

    /**
     * @return Account
     */
    public function getAccount() {
        if (!$this->account && $this->accountId) {
            /** @var AccountRepository $repo */
            $repo = \EntityManager::getRepository(Account::class);
            $this->account = $repo->findById($this->accountId);
        }

        return $this->account;
    }

    /**
     * @param Account $account
     */
	public function setAccount(Account $account) {
        $this->account = $account;
        $this->accountId = $account->getAccountId();
    }

    public function getAccountId() {
        return $this->accountId;
    }

	public function setAccountId(int $val) {
        $this->accountId = $val;
    }

    public function getMarketingSystem() {
        return $this->marketingSystem;
    }

	public function setMarketingSystem(MarketingSystem $marketingSystem) {
        $this->marketingSystem = $marketingSystem;
    }
        
	public function getConversionDate() {
        return $this->conversionDate;
    }

    public function setConversionDate($conversionDate) {
        $this->conversionDate = $conversionDate;
    }
    
    public function getData() {
    	return $this->data;
    }
    
    public function setData(array $data) {
    	$this->data = $data;
    }
    
    public function addData($name, $value) {
    	$this->data[$name] = $value;
    }
	
    public function getDataValue($name) {
    	$result = "";
    	
    	if (array_key_exists($name, $this->data)) {
    		$result = $this->data[$name];
    	}
    	
    	return $result;
    }
    
    public function getHasOffersTransactionId() {
    	return $this->getDataValue(self::HAS_OFFERS_URL_PARAM_TRANSACTION_ID);
    }
    
    public function getHasOffersOfferId() {
    	return $this->getDataValue(self::HAS_OFFERS_URL_PARAM_OFFER_ID);
    }
    
    public function getHasOffersAffiliateId() {
    	return $this->getDataValue(self::HAS_OFFERS_URL_PARAM_AFFILIATE_ID);
    }
    
    public static function getHasOffersUrlParams() {
        return array(
            self::HAS_OFFERS_URL_PARAM_AFF_ID,
            self::HAS_OFFERS_URL_PARAM_AFFILIATE_ID,
            self::HAS_OFFERS_URL_PARAM_AFFILIATE_NAME,
            self::HAS_OFFERS_URL_PARAM_SOURCE,
            self::HAS_OFFERS_URL_PARAM_AFF_SUB,
            self::HAS_OFFERS_URL_PARAM_AFF_SUB2,
            self::HAS_OFFERS_URL_PARAM_AFF_SUB3,
            self::HAS_OFFERS_URL_PARAM_AFF_SUB4,
            self::HAS_OFFERS_URL_PARAM_AFF_SUB5,
            self::HAS_OFFERS_URL_PARAM_OFFER_ID,
            self::HAS_OFFERS_URL_PARAM_OFFER_NAME,
            self::HAS_OFFERS_URL_PARAM_OFFER_URL_ID,
            self::HAS_OFFERS_URL_PARAM_OFFER_FILE_ID,
            self::HAS_OFFERS_URL_PARAM_FILE_NAME,
            self::HAS_OFFERS_URL_PARAM_ADVERTISER_ID,
            self::HAS_OFFERS_URL_PARAM_COUNTRY_CODE,
            self::HAS_OFFERS_URL_PARAM_REGION_CODE,
            self::HAS_OFFERS_URL_PARAM_PARAMS,
            self::HAS_OFFERS_URL_PARAM_TRANSACTION_ID,
            self::HAS_OFFERS_URL_PARAM_DATE,
            self::HAS_OFFERS_URL_PARAM_TIME,
            self::HAS_OFFERS_URL_PARAM_DATETIME,
            self::HAS_OFFERS_URL_PARAM_IP,
            self::HAS_OFFERS_URL_PARAM_OFFER_REF,
            self::HAS_OFFERS_URL_PARAM_AFFILIATE_REF,
            self::HAS_OFFERS_URL_PARAM_ADVERTISER_REF,
        );
    }
    
    public function populate($row) {
        foreach($row as $key => $value) {
            if($key == "account_id") {
            	$this->accountId = $value;
            }
            else if($key == "conversion_date") {
				$this->setConversionDate($value);
            }
            else if($key == "marketing_system_id") {
            	$this->getMarketingSystem()->setMarketingSystemId($value);
            }
        }
    }
    
    public function toArray() {
        return array(
            "account_id" => $this->getAccountId(),
            "marketing_system_id" => $this->getMarketingSystem()->getMarketingSystemId(),
        	"conversion_date" => $this->getConversionDate(),
        );
    }       
}
