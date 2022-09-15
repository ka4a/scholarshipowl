<?php

namespace ScholarshipOwl\Data\Entity\Marketing;

use ScholarshipOwl\Data\Entity\AbstractEntity;


class MarketingSystem extends AbstractEntity {
    const HAS_OFFERS = 1;
    
    private $marketingSystemId;
    private $name;
    private $urlIdentifier;
        
	
	public function __construct() {
        $this->marketingSystemId = 0;
        $this->name = "";
        $this->urlIdentifier = "";
    }

    public function getMarketingSystemId() {
        return $this->marketingSystemId;
    }

	public function setMarketingSystemId($marketingSystemId) {
        $this->marketingSystemId = $marketingSystemId;
        
        $types = self::getMarketingSystemNames();
        if(array_key_exists($marketingSystemId, $types)) {
        	$this->name = $types[$marketingSystemId];                
        }
    }

    public function getName() {
        return $this->name;
    }

	public function setName($name) {
        $this->name = $name;
    }

    public function getUrlIdentifier() {
        return $this->urlIdentifier;
    }

    public function setUrlIdentifier($urlIdentifier) {
        $this->urlIdentifier = $urlIdentifier;
    }
    
    public function populate($row) {
        foreach($row as $key => $value) {
            if($key == "marketing_system_id") {
            	$this->setMarketingSystemId($value);
            }
            else if($key == "name") {
            	$this->setName($value);
            }
            else if($key == "url_identifier") {
            	$this->setUrlIdentifier($value);
            }
        }
    }
    
    public function toArray() {
        return array(
            "marketing_system_id" => $this->getMarketingSystemId(),
            "name" => $this->getName(),
            "url_identifier" => $this->getUrlIdentifier()
        );
    }
    
    public function __toString() {
    	return $this->name;
    }
    
    public static function getMarketingSystemNames() {
        return array(
        	self::HAS_OFFERS => "Has Offers"
        );
    }
    
    public static function getMarketingSystemIndentifiers() {
        return array(
        	self::HAS_OFFERS => "transaction_id"
        );
    }
    
    public static function getMarketingSystemIdByIndentifier($identifier) {
    	$result = null;
    	
    	$identifiers = self::getMarketingSystemIndentifiers();
    	foreach ($identifiers as $marketingSystemId => $name) {
    		if ($name == $identifier) {
    			$result = $marketingSystemId;
    			break;
    		}
    	}
    	
    	return $result;
    }
}
