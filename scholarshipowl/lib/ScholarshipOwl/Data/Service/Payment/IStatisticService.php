<?php

/**
 * IStatisticService
 *
 * @package     ScholarshipOwl\Data\Service\Payment
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	19. May 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Payment;


interface IStatisticService {
	// Subscription Statistics
	public function getTopPrioritySubscriptions($accountIds);
	public function hasUnlimitedSubscriptions($accountIds);
	public function hasPaidSubscriptions($accountIds);
}
