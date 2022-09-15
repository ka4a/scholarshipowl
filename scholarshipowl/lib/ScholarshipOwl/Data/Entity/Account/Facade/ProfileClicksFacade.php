<?php

/**
 * ProfileClicksFacade
 *
 * @package     ScholarshipOwl\Data\Entity\Account\Facade
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	16. February 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Account\Facade;

use ScholarshipOwl\Data\Entity\Info\DegreeType;
use ScholarshipOwl\Data\Entity\Account\Profile;


class ProfileClicksFacade {
	public static function getDegreeTypeName(Profile $profile) {
		$result = "";
		
		if($degreeTypeId = $profile->getDegreeType()->getDegreeTypeId()) {
            switch ($degreeTypeId) {
                case DegreeType::UNDECIDED:
                    $result = "No Filter";
                    break;
                case DegreeType::CERTIFICATE:
                    $result = "Certificate";
                    break;
                case DegreeType::ASSOCIATES_DEGREE:
                    $result = "Associate";
                    break;
                case DegreeType::BACHELORS_DEGREE:
                    $result = "Bachelor";
                    break;
                case DegreeType::GRADUATE_CERTIFICATE:
                    $result = "Certificate";
                    break;
                case DegreeType::MASTERS_DEGREE:
                    $result = "Master";
                    break;
                case DegreeType::DOCTORAL_PHD:
                    $result = "Doctorate";
                    break;
            }
        }
		
		return $result;
	}
	
	public static function getCareerGoalName(Profile $profile) {
		$result = "";

        if($study = $profile->getCareerGoal()->getCareerGoalId()) {
            switch ($study) {
                case 1:
                    $result = "Arts and Humanities";
                    break;
                case 2:
                    $result = "";   //  Beauty or Cosmetology
                    break;
                case 3:
                    $result = "Business and MBA";
                    break;
                case 4:
                    $result = "Computers and IT";
                    break;
                case 5:
                    $result = "";   //  Culinary Arts
                    break;
                case 6:
                    $result = "Health and Medicine/Nursing";
                    break;
                case 7:
                    $result = "Criminal Justice / Social Sciences";
                    break;
                case 8:
                    $result = "Education and Teaching";
                    break;
                case 9:
                    $result = "";   //  Vocational / Technical
                    break;
                case 10:
                    $result = "General Education";   //  Other
                    break;
            }
        }

		return $result;
	}
	
	public static function getStudyOnlineValue(Profile $profile) {
		$result = "";
        if($campus = $profile->getStudyOnline()) {
            switch ($campus) {
                case "no":
                    $result = "Campus";
                    break;
                case "yes":
                    $result = "Online";
                    break;
                case "maybe":
                    $result = "Both";
                    break;
            }
        }
		
		return $result;
	}
}


