<?php
/**
 * Created by PhpStorm.
 * User: mopacic
 * Date: 9/17/15
 * Time: 7:58 PM
 */

namespace ScholarshipOwl\Data\Service\Mission;

use App\Entity\FeaturePaymentSet;
use App\Entity\Scholarship;
use ScholarshipOwl\Data\Entity\Payment\Package;
use ScholarshipOwl\Data\Service\AbstractService;

class MissionOrderService extends AbstractService
{
    public function getMissionsAndPackages()
    {
        $missionService = new MissionService();
        $missionPackages = $missionService->getMissionsPackages();

        $missionsAndPackages = [];
        $sql = "select `type`, item_id from package_priorities";

        $savedLayout = [];
        $savedLayout['mission'] = [];
        $savedLayout['package'] = [];

        foreach(\DB::connection()->getPdo()->query($sql) as $row) {
            $savedLayout[$row['type']][] = $row['item_id'];
            foreach($missionPackages as $mission){
                if($row['item_id'] == $mission->getMissionId()){
                    /** @var $mission \ScholarshipOwl\Data\Entity\Mission\Mission */
                    $missionsAndPackages[] = ['missionId'=> $mission->getMissionId(),'id'=> $mission->getMissionId(), 'type' => 'mission', 'data' => $mission];
                }
            }
//            foreach($packages as $package){
//                /** @var $package \ScholarshipOwl\Data\Entity\Payment\Package */
//                if($row['item_id'] == $package->getPackageId()){
//                    $missionsAndPackages[] = ['packageId'=> $package->getPackageId(), 'id'=> $package->getPackageId(), 'type' => 'package', 'data' => $package];
//                }
//            }
        }

        foreach($missionPackages as $mission){
            if(!in_array($mission->getMissionId(),$savedLayout['mission'])){
                $missionsAndPackages[] = ['missionId'=> $mission->getMissionId(),'id'=> $mission->getMissionId(), 'type' => 'mission', 'data' => $mission];
            }
        }

//        foreach($packages as $package){
//            if(!in_array($package->getPackageId(),$savedLayout['package'])){
//                $missionsAndPackages[] = ['packageId'=> $package->getPackageId(), 'id'=> $package->getPackageId(), 'type' => 'package', 'data' => $package];
//            }
//        }


        foreach (FeaturePaymentSet::packages() as $package) {
            $missionsAndPackages[] = ['packageId'=> $package->getPackageId(), 'id'=> $package->getPackageId(), 'type' => 'package', 'data' => $package];
        }


        return $missionsAndPackages;
    }

    public function getMissionGoalOrder($missionId){
        $sql = "
            select mission_goal_properties from mission_goal_priorities where mission_id = '{$missionId}'
        ";
        $result = false;
        $goals = "";
        foreach(\DB::connection()->getPdo()->query($sql) as $row) {
            $goals = $row['mission_goal_properties'];
        }
        if(!empty($goals)){
            $sql = "select `name`, `mission_goal_id` from mission_goal where mission_goal_id in ({$goals})";
            $result = [];
            $queryRes = \DB::connection()->getPdo()->query($sql);
            $goals = explode(',',$goals);


            $mgArray = [];
            foreach($queryRes as $row) {
                $mgArray[(int)$row['mission_goal_id']] = $row['name'];
            }

            foreach($goals as $goal){
                foreach($mgArray as $id => $name) {
                    if((int)$goal == (int)$id){
                        $result[] = [ $id,  $name];
                    }
                }
            }
        }
        return $result;
    }
}
