<?php

/**
 * StatisticDailyType
 *
 * @package     ScholarshipOwl\Data\Entity\Statistic
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	03. February 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Statistic;

use ScholarshipOwl\Data\Entity\AbstractEntity;


class StatisticDailyType extends AbstractEntity {
	const NEW_ACCOUNTS = 1;
	const NEW_PAYING_ACCOUNTS = 2;
	const TOTAL_ACCOUNTS = 3;
	const TOTAL_PAYING_ACCOUNTS = 4;
	const TOTAL_LOGGED_ACCOUNTS = 5;
    const NEW_ACCOUNTS_WITH_FREE_APPLICATIONS = 6;
    const NEW_ACCOUNTS_WITH_PAID_APPLICATIONS = 7;
    const FREE_APPLICATIONS_SENT = 8;
    const PAID_APPLICATIONS_SENT = 9;
    const DEPOSIT_AMOUNT = 10;
    const PACKAGES_SOLD = 11;
    const SCHOLARSHIP_APPLICATIONS_SOLD = 12;
	const TOTAL_FREE_APPLICATIONS_SENT = 13;
	const TOTAL_PAID_APPLICATIONS_SENT = 14;
	const DEPOSIT_CORRECTIONS = 15;
	const DEPOSIT_CORRECTION_AMOUNT = 16;
    const FREE_TRIAL_SUBSCRIPTIONS = 17;
    const FREE_TRIAL_1ST_CHARGE = 18;

	private $statisticDailyTypeId;
	private $name;


    /**
     * @param $type
     *
     * @return static
     */
    public static function create($type)
    {
        return new static($type);
    }

    public function __construct($statisticDailyTypeId = null) {
		$this->statisticDailyTypeId = null;
		$this->name = "";

		$this->setStatisticDailyTypeId($statisticDailyTypeId);
	}

	public function setStatisticDailyTypeId($statisticDailyTypeId) {
		$this->statisticDailyTypeId = $statisticDailyTypeId;

		$types = self::getStatisticDailyTypes();
		if(array_key_exists($statisticDailyTypeId, $types)) {
			$this->name = $types[$statisticDailyTypeId];
		}
	}

	public function getStatisticDailyTypeId() {
		return $this->statisticDailyTypeId;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	public static function getStatisticDailyTypes() {
		return array(
			self::NEW_ACCOUNTS => "New Accounts",
			self::NEW_PAYING_ACCOUNTS => "New Paying Accounts",
			self::TOTAL_ACCOUNTS => "Total Accounts",
			self::TOTAL_PAYING_ACCOUNTS => "Total Paying Accounts",
			self::TOTAL_LOGGED_ACCOUNTS => "Total Logged Accounts",
            self::NEW_ACCOUNTS_WITH_FREE_APPLICATIONS => "New Accounts With Free Applications",
            self::NEW_ACCOUNTS_WITH_PAID_APPLICATIONS => "New Accounts With Paid Applications",
            self::FREE_APPLICATIONS_SENT => "Number Of Free Applications Sent By New Accounts",
            self::PAID_APPLICATIONS_SENT => "Number Of Paid Applications Sent By New Accounts",
            self::DEPOSIT_AMOUNT => "Deposited Amount",
            self::PACKAGES_SOLD => "Packages Sold",
            self::SCHOLARSHIP_APPLICATIONS_SOLD => "Applications Sold",
            self::TOTAL_FREE_APPLICATIONS_SENT => "Number Of Free Applications Sent",
            self::TOTAL_PAID_APPLICATIONS_SENT => "Number Of Paid Applications Sent",
            self::DEPOSIT_CORRECTIONS => "Number of deposit corrections",
            self::DEPOSIT_CORRECTION_AMOUNT => "Deposit correction amount",
            self::FREE_TRIAL_SUBSCRIPTIONS => 'Free trial subscriptions',
            self::FREE_TRIAL_1ST_CHARGE => 'Free trial 1st charge',
		);
	}

	public function __toString() {
		return $this->name;
	}

	public function populate($row) {
		foreach($row as $key => $value) {
			if($key == "statistic_daily_type_id") {
				$this->setStatisticDailyTypeId($value);
			}
			else if($key == "name") {
				$this->setName($value);
			}
		}
	}

	public function toArray() {
		return array(
			"statistic_daily_type_id" => $this->getStatisticDailyTypeId(),
			"name" => $this->getName()
		);
	}
}
