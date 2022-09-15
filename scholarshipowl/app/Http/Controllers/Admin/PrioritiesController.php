<?php

namespace App\Http\Controllers\Admin;

//use ScholarshipOwl\Data\Entity\Marketing\ABTest;
use Index\Controller\HomeController;
use ScholarshipOwl\Data\Service\Cms\CmsService;
use ScholarshipOwl\Data\Entity\Cms\Cms;
use ScholarshipOwl\Data\Service\Marketing\AffiliateService;
use ScholarshipOwl\Data\Service\Mission\MissionOrderService;
use ScholarshipOwl\Data\Service\Mission\MissionService;
use ScholarshipOwl\Data\Service\Payment\PackageService;
use ScholarshipOwl\Http\JsonModel;
use ScholarshipOwl\Http\ViewModel;
use ScholarshipOwl\Util\Storage;


class PrioritiesController extends BaseController {

    public function missiongoalsAction(){

        $model = new ViewModel("admin/priorities/missiongoals");

        $missionOrderService = new MissionOrderService();
        $missionsAndPackages = $missionOrderService->getMissionsAndPackages();
        $packages = [];
        $missionPackages = [];
        $missionGoalConfig = [];
        foreach($missionsAndPackages as $mp){
            if($mp['type'] == 'package'){
                $packages[$mp['id']] = $mp['data'];
            } else if($mp['type'] == 'mission'){
                $missionPackages[$mp['id']] = $mp['data'];
                $missionGoalConfig[$mp['id']] = [];
                $missionOrderService = new MissionOrderService();
                $order = $missionOrderService->getMissionGoalOrder($mp['data']->getMissionId());
                if(!empty($order)){
                    foreach($order as $missionGoal){
                        $missionGoalConfig[$mp['id']][] = [$missionGoal[0],$missionGoal[1]];
                    }
                } else {
                    foreach($mp['data']->getMissionGoals() as $missionGoal){
                        $missionGoalConfig[$mp['id']][] = [$missionGoal->getMissionGoalId(),$missionGoal->getName()];
                    }
                }
            }
        }

        $data = array(
            "user" =>  $this->getLoggedUser(),
            "missions" => $missionGoalConfig,
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard"
            ),
            "title" => "Mission Goals",
            "active" => "affiliates"
        );

        $model->setData($data);
        return $model->send();

    }


    public function packagesAction(){
        $model = new ViewModel("admin/priorities/packages");
        $missionOrderService = new MissionOrderService();
        $mps = $missionOrderService->getMissionsAndPackages();
        $data = array(
            "user" => $this->getLoggedUser(),
            "mps" => $mps,
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard"
            ),
            "title" => "Packages Priorities",
            "active" => "packages"
        );

        $model->setData($data);
        return $model->send();
    }


    public function saveLayoutConfigurationAction(){
        $model = new JsonModel();
        $input = $this->getAllInput();

        $str = "";
        $layout = [];
        foreach($input['layout'] as $item){
            $exploded = explode('-',$item);
            $layout[] = [$exploded[0],$exploded[1]];
        }

        $sql = "DELETE FROM package_priorities";
        \DB::connection()->getPdo()->exec( $sql );
        foreach($layout as $item){
            $sql = "INSERT INTO package_priorities (`type`,`item_id`) values ('{$item[0]}','{$item[1]}')";
            \DB::connection()->getPdo()->exec( $sql );
        }
        $model->setStatus(JsonModel::STATUS_OK);
        $model->setMessage("Layout saved !");
        return $model->send();
    }


    public function saveMissionGoalsAction(){
        $model = new JsonModel();
        $input = $this->getAllInput();

        $str = "";
        $layout = [];
        foreach($input['layout'] as $item){
            $exploded = explode('-',$item);
            $layout[] = $exploded[1];
        }

        $sql = "DELETE FROM mission_goal_priorities where mission_id = {$exploded[0]}";
        \DB::connection()->getPdo()->exec( $sql );

        $sql = "REPLACE INTO mission_goal_priorities (`mission_id`,`mission_goal_properties`) values ('{$exploded[0]}','".implode(',',$layout)."')";
        \   DB::connection()->getPdo()->exec( $sql );

        $model->setStatus(JsonModel::STATUS_OK);
        $model->setMessage("Layout saved !");
        $model->setData($layout);
        return $model->send();
    }
}