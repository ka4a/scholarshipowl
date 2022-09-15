<?php

/**
 * IAccountHasoffersFlagService
 *
 * @package     ScholarshipOwl\Data\Service\Marketing
 * @version     1.0
 * @author      Ivan Krkotic <ivan.krkotic@gmail.com>
 *
 * @created    	14. March 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Marketing;

use ScholarshipOwl\Data\Entity\Marketing\AccountHasoffersFlag;


interface IAccountHasoffersFlagService {
	public function getFlagForAccount($accountId);
	public function addFlagForAccount($accountId);

	// Updates is_sent
	public function setSent($accountId);
}
