<?php

namespace App\Http\Controllers\Index;


use App\Services\Marketing\CoregService;
use App\Entity\Marketing\Submission;
use App\Services\Marketing\SubmissionService;
use Doctrine\ORM\EntityManager;
use ScholarshipOwl\Data\Service\Marketing\DaneMediaService;
use ScholarshipOwl\Data\Service\Marketing\ZuUsaService;
use ScholarshipOwl\Http\JsonModel;

class CoregController extends BaseController
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var CoregService
     */
    protected $cs;

    /**
     * @var SubmissionService
     */
    protected $ss;

    /**
     * CoregController constructor.
     * @param CoregService $cs
     * @param SubmissionService $ss
     */
    public function __construct(CoregService $cs, SubmissionService $ss)
    {
        parent::__construct();

        $this->cs = $cs;
        $this->ss = $ss;
    }

    public function daneMediaAction(){
		try {
			$service = new DaneMediaService();
			$data = array();
			$data["user"] = $this->getLoggedUser();

			$campaignsResult = $service->getAvailableCampaignsForAccount($data["user"]);

			if(!empty($campaignsResult)){
				$selectedCampaign = reset($campaignsResult);

				$selectedCampaignId = $selectedCampaign->getDaneMediaCampaignId();
				$selectedCampaignSubmissionValue = $selectedCampaign->getSubmissionValue();
				$campuses = array();
				foreach ($campaignsResult[$selectedCampaignId]->campuses as $campus){
					if(!empty($campus->getDaneMediaCampusId())){
						$campuses[] = $campus;
					}
				}

				$campaignSettings = \Config::get("danemedia.".$selectedCampaignId);

				$data["campuses"] = $campuses;
				$data["formId"] = $selectedCampaignId;
				$data["campaignId"] = $selectedCampaignSubmissionValue;
				$data["campaignSettings"] = $campaignSettings;

				$plugin = \Session::get("plugin.danemedia");

				if($plugin){
					$data["daneMediaText"] = $this->cs->getCoregPlugin($plugin->getCoregPluginId())->getText();
				}

				$file = "includes/coreg/danemedia";
				$model = $this->getCommonUserViewModel($file, $data);
				return $model->send();
			}else{
				return \Redirect::to(setting("register.redirect_page"));
			}

		}catch (\Exception $exc){
			$this->handleException($exc);
		}
	}

	/**
	 * Post Dane Media Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Ivan Krkotic <ivan.krkotic@gmail.com>
	 */
	public function postDaneMediaAction() {
		$model = new JsonModel();
		try {
			$input = $this->getAllInput();

			$errors = array();

			if (empty($input["consent"])) {
				$errors["consent"] = "You must agree with the terms and conditions before you continue!";
			}

			if (empty($input["programid"])) {
				$errors["programid"] = "You must choose a valid program!";
			}

			if(empty($errors)) {
				unset($input["_token"]);
				$daneMediaService = new DaneMediaService();

                $this->ss->insertSubmission($this->getLoggedUser()->getAccountId(), \Request::getClientIp(), Submission::NAME_DANE_MEDIA, json_encode($input));

				$campaign = $daneMediaService->getDaneMediaCampaign($input["form_id"]);

				if($campaign){
					if(!empty($campaign->getDailyCap())){
						$daneMediaService->updateCampaignCapping($campaign->getDaneMediaCampaignId(), "day");
					}

					$daneMediaService->updateCampaignCapping($campaign->getDaneMediaCampaignId());
				}

				$model->setStatus(JsonModel::STATUS_REDIRECT);
				$model->setData($this->isMobile()?setting("register.redirect_page_mobile"):setting("register.redirect_page"));
			}else{
				$model->setStatus(JsonModel::STATUS_ERROR);
				$model->setMessage("Please fix errors !");
				$model->setData($errors);
			}
		}
		catch (\Exception $exc) {
			$this->handleException($exc);
		}
		return $model->send();
	}

	public function getDaneMediaPrograms(){
		$model = new JsonModel();
		$result = array();

		$input = $this->getAllInput();
		$user = $this->getLoggedUser();
		$service = new DaneMediaService();
		$programs = $service->getAvailablePrograms($user, $input);

		if(empty($programs)){
			$result[] = ["submission_value" => "", "display_value" => "No matching programs found"];
		}else{
			foreach($programs as $program){
				if(!empty($program->submission_value)) {
					$result[] = ["submission_value" => $program->submission_value, "display_value" => $program->display_value];
				}
			}
		}
		$model->setStatus(JsonModel::STATUS_OK);
		$model->setData($result);
		return $model->send();
	}

    public function zuUsaAction(){
        try {
            $service = new ZuUsaService();
            $data = array();
            $data["user"] = $this->getLoggedUser();

            $campaignsResult = $service->getAvailableCampaignsForAccount($data["user"]);

            if(!empty($campaignsResult)){
                $selectedCampaign = reset($campaignsResult);

                $selectedCampaignId = $selectedCampaign->getZuUsaCampaignId();
                $campuses = array();
                foreach ($campaignsResult[$selectedCampaignId]->campuses as $campus){
                    if(!empty($campus->getZuUsaCampusId())){
                        $campuses[] = $campus;
                    }
                }

                $campaignSettings = \Config::get("zuusa.".$selectedCampaignId);

                $data["campuses"] = $campuses;
                $data["formId"] = $selectedCampaignId;
                $data["campaignId"] = $selectedCampaign->getSubmissionValue();
                $data["campaignSettings"] = $campaignSettings;

                $plugin = \Session::get("plugin.zuusa");

                if($plugin){
                    $data["zuUsaText"] = $this->cs->getCoregPlugin($plugin->getCoregPluginId())->getText();
                }

                $file = "includes/coreg/zuusa";
                $model = $this->getCommonUserViewModel($file, $data);
                return $model->send();
            }else{
                return \Redirect::to(setting("register.redirect_page"));
            }

        }catch (\Exception $exc){
            $this->handleException($exc);
        }
    }

    /**
     * Post Zu Usa Action
     *
     * @access public
     */
    public function postZuUsaAction() {
        $model = new JsonModel();
        try {
            $input = $this->getAllInput();

            $errors = array();

            if($input["form_id"] != 4 && $input["form_id"] != 7 && $input["form_id"] != 8 && $input["form_id"] != 9 && $input["form_id"] != 10 && $input["form_id"] != 11 && $input["form_id"] != 19){
                if (empty($input["consent"]) && empty($input["concent"]) && empty($input["TCPA"]) && empty($input["tcpaconsent"]) && empty($input["agree"])) {
                    $errors["consent"] = "You must agree with the terms and conditions before you continue!";
                    $errors["tcpaconsent"] = "You must agree with the terms and conditions before you continue!";
                }
            }

            if (empty($input["program_id"])) {
                $errors["program_id"] = "You must choose a valid program!";
            }

            if($input["form_id"] == 4){
                if (empty($input["TCPAcompliant"])) {
                    $input["TCPAcompliant"] = "No";
                }
            }

            if(empty($errors)) {
                unset($input["_token"]);
                $zuUsaService = new ZuUsaService();

                $this->ss->insertSubmission($this->getLoggedUser()->getAccountId(), \Request::getClientIp(), Submission::NAME_ZU_USA, json_encode($input));

                $campaign = $zuUsaService->getZuUsaCampaign($input["form_id"]);

                if($campaign){
                    if(!empty($campaign->getDailyCap())){
                        $zuUsaService->updateCampaignCapping($campaign->getZuUsaCampaignId(), "day");
                    }

                    $zuUsaService->updateCampaignCapping($campaign->getZuUsaCampaignId());
                }

                $model->setStatus(JsonModel::STATUS_REDIRECT);
                $model->setData($this->isMobile()?setting("register.redirect_page_mobile"):setting("register.redirect_page"));
            }else{
                $model->setStatus(JsonModel::STATUS_ERROR);
                $model->setMessage("Please fix errors !");
                $model->setData($errors);
            }
        }
        catch (\Exception $exc) {
            $this->handleException($exc);
        }
        return $model->send();
    }

    public function getZuUsaPrograms(){
        $model = new JsonModel();
        $result = array();

        $input = $this->getAllInput();
        $user = $this->getLoggedUser();
        $service = new ZuUsaService();
        $programs = $service->getAvailablePrograms($user, $input);

        if(empty($programs)){
            $result[] = ["submission_value" => "", "display_value" => "No matching programs found"];
        }else{
            foreach($programs as $program){
                if(!empty($program->submission_value)) {
                    $result[] = ["submission_value" => $program->submission_value, "display_value" => $program->display_value];
                }
            }
        }
        $model->setStatus(JsonModel::STATUS_OK);
        $model->setData($result);
        return $model->send();
    }
}