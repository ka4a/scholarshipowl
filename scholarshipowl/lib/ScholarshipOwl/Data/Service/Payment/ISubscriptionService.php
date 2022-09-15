<?php

/**
 * ISubscriptionService
 *
 * @package     ScholarshipOwl\Data\Service\Payment
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	25. November 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Payment;

use ScholarshipOwl\Data\Entity\Payment\Package;
use ScholarshipOwl\Data\Entity\Payment\Transaction;


interface ISubscriptionService {

	public function getScholarshipApplicationsDated($startDate = '', $endDate = '');
	public function getUniqueCustomersDated($startDate = '', $endDate = '');
	public function getTotalAmountDated($startDate = '', $endDate = '');
	public function getTotalsByPackageDated($startDate = '', $endDate = '');

    public function getTopPrioritySubscription($accountId);
    public function getTopPrioritySubscriptionWithCredit($accountId);
    public function getLowestPrioritySubscriptionWithCredit($accountId);
    public function getUnlimitedUserSubscription($accountId);
    public function getTotalCredit($accountId);
    public function getTotalScholarships($accountId);
    public function getTotalSubscriptionsCount($accountId);
	public function getBoughtPackages();

	public function getPotentialExpiredSubscriptions();
	public function expireSubscription($subscriptionId);
	public function cancelSubscription($subscriptionId);
	public function expireSubscriptions($subscriptionIds);

}
