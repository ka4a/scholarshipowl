<?php

/**
 * IProfileService
 *
 * @package     ScholarshipOwl\Data\Service\Account
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	29. October 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Account;

use ScholarshipOwl\Data\Entity\Account\Profile;
use ScholarshipOwl\Data\Entity\Scholarship\Scholarship;


interface IProfileService {
	public function getProfile($accountId);
	
	public function getBasicProfile($accountId);
	public function getEducationProfile($accountId);
	public function getInterestsProfile($accountId);
	public function getLocationProfile($accountId);
	
	public function setBasicProfile(Profile $profile);
	public function setEducationProfile(Profile $profile);
	public function setInterestsProfile(Profile $profile);
	public function setLocationProfile(Profile $profile);
	
	public function isEligible(Profile $profile, Scholarship $scholarship);
}
