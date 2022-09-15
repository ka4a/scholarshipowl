<?php

/**
 * StatisticDaily
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


class StatisticDaily extends AbstractEntity {
	private $statisticDailyType;
	private $statisticDailyDate;
	private $value;

	
	public function __construct() {
		$this->statisticDailyType = new StatisticDailyType();
		$this->statisticDailyDate = null;
		$this->value = 0;
	}
	
	public function getStatisticDailyType(){
		return $this->statisticDailyType;
	}
	
	public function setStatisticDailyType(StatisticDailyType $statisticDailyType){
		$this->statisticDailyType = $statisticDailyType;
	}
	
	public function getStatisticDailyDate(){
		return $this->statisticDailyDate;
	}
	
	public function setStatisticDailyDate($statisticDailyDate){
		$this->statisticDailyDate= $statisticDailyDate;
	}

	public function getValue(){
		return $this->value;
	}
	
	public function setValue($value){
		$this->value = $value;
	}
	
	public function populate($row) {
		foreach($row as $key => $value) {
			if($key == "statistic_daily_type_id") {
				$this->getStatisticDailyType()->setStatisticDailyTypeId($value);
			}
			else if($key == "statistic_daily_date") {
				$this->setStatisticDailyDate($value);
			}
			else if($key == "value") {
				$this->setValue($value);
			}
		}
	}
	
	public function toArray() {
		return array(
			"statistic_daily_type_id" => $this->getStatisticDailyType()->getStatisticDailyTypeId(),
			"statistic_daily_date" => $this->statisticDailyDate(),
			"value" => $this->getValue(),
		);
	}
}
