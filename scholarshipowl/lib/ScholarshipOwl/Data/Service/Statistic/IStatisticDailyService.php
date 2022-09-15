<?php

/**
 * IStatisticDailyService
 *
 * @package     ScholarshipOwl\Data\Service\Statistic
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	03. February 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Statistic;

use ScholarshipOwl\Data\Entity\Statistic\StatisticDailyType;


interface IStatisticDailyService {
	public function search($params = array(), $limit = "");
	
	public function saveNewAccountsStatistic();
	public function saveNewPayingAccountsStatistic();
	public function saveTotalAccountsStatistic();
	public function saveTotalPayingAccountsStatistic();
	public function saveTotalLoggedAccountsStatistic();
	public function saveNumberOfNewAccountsWithFreeApplicationsSentStatistic();
	public function saveNumberOfNewCustomersWithPaidApplicationsSentStatistic();
	public function saveNumberOfFreeApplicationsSentStatistic();
	public function saveNumberOfPaidApplicationsSentStatistic();
	public function saveDepositAmountStatistic();
	public function savePackagesSoldStatistic();
	public function saveScholarshipApplicationsSoldStatistic();
	public function saveTotalNumberOfFreeApplicationsSentStatistic();
	public function saveTotalNumberOfPaidApplicationsSentStatistic();
	public function saveDepositCorrectionsStatistic();
	public function saveDepositCorrectionAmountStatistic();
}
