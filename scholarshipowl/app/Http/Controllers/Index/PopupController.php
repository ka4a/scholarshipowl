<?php

namespace App\Http\Controllers\Index;

use ScholarshipOwl\Data\Service\Mission\MissionService;
use ScholarshipOwl\Http\ViewModel;

class PopupController extends BaseController
{

    public function clicksAction(){
        $model = new ViewModel("popup/clicks");
        try {
            $model->user = $this->getLoggedUser();
        }catch (\Exception $exc){
            $this->handleException($exc);
        }

        return $model->send();
    }

	public function loanAction()
    {
        $url = '/';

		try {
			if ($user = $this->getLoggedUser()) {
                $profile = $user->getProfile();
                $settings = is_production() ? "LoanProduction" : "LoanStaging";

                $config = $this->getConfig("scholarshipowl.submission." . $settings);

                $data = array(
                    "fName" => $profile->getFirstName(),
                    "lName" => $profile->getLastName(),
                    "email" => $user->getEmail(),
                    "street" => $profile->getAddress(),
                    "city" => $profile->getCity(),
                    "state" => $profile->getState()->getAbbreviation(),
                    "postalCode" => $profile->getZip(),
                    "homePhone" => $profile->getPhone(),
                    "type" => "Personal",
                    "dob" => $profile->getDateOfBirthMonth() . "/" . $profile->getDateOfBirthDay() . "/" . $profile->getDateOfBirthYear(),
                );

                $data = array_merge($data, $config["auth"]);

                $url = $config["url"] . "?" . http_build_query($data);
            }
		} catch (\Exception $exc){
			$this->handleException($exc);
		}

        return $this->redirect($url);
	}

    public function redirectAction($goalId)
    {
        $redirect = $this->redirect('/');
        $account = $this->getLoggedUser();
        
        if ($goalId && $account) {
            $model = new ViewModel("popup/redirect");
            try {
                $accountId = $account->getAccountId();
                $missionService = new MissionService();
                $model->redirectMessage = $missionService->getRedirectMessage($goalId);
                $model->redirectTime = $missionService->getRedirectTime($goalId)?$missionService->getRedirectTime($goalId):10;
                $model->generatedUrl = sprintf("%s/affiliate/goal/%d/%d", url()->current(), $goalId, $accountId);
                $model->isMobile = $this->isMobile();

                $redirect = $model->send();

            } catch (\Exception $exc){
                $this->handleException($exc);
            }
        }

        return $redirect;
    }

}