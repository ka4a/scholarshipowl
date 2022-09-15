<?php

namespace App\Services\SuperCollege;

use App\Entity\Account;
use App\Entity\SuperCollegeScholarship;
use App\Entity\SuperCollegeScholarshipMatch;
use Carbon\Carbon;
use ScholarshipOwl\Doctrine\ORM\QueryIterator;


class SuperCollegeService
{
    const WSDL = "http://dazoodle.com/api/api_search_owl_v2.cfc?wsdl";

    const RETURN_FORMAT_XML = "xml";
    const RETURN_FORMAT_WDDX = "wddx";


    private $apiKey;
    private $siteUrl;
    private $siteId;
    private $returnFormat;

    private $soapClient;


    public function __construct()
    {
        $this->apiKey = "";
        $this->siteUrl = "";
        $this->siteId = "";
        $this->returnFormat = self::RETURN_FORMAT_XML;

        $this->soapClient = null;
    }

    public function findMatches($params = array())
    {
        $result = array();
        $client = $this->getSoapClient();

        $params = $this->prepareParams($params);
        $this->assertParams($params);

        $response = call_user_func_array(array($client, "findmatches"), array_values($params));
        if (!empty($response)) {
            $response = $response->scholarships;

            foreach ($response->award as $scholarship) {
                $result[] = (array)$scholarship;
            }
        }

        return $result;
    }

    public function getDetails($uuid, $userId = "0")
    {
        $result = array();
        $client = $this->getSoapClient();

        $params = array();
        $params["returnf"] = $this->getReturnFormat();
        $params["apikey"] = $this->getApiKey();
        $params["siteurl"] = $this->getSiteUrl();
        $params["siteid"] = $this->getSiteId();
        $params["userid"] = $userId;
        $params["s_uuid"] = $uuid;
        $this->assertParams($params);

        $response = call_user_func_array(array($client, "getdetails"), array_values($params));
        $response = $response->award;
        $result = $response->details;

        return $result;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getSiteUrl()
    {
        return $this->siteUrl;
    }

    public function setSiteUrl($siteUrl)
    {
        $this->siteUrl = $siteUrl;
    }

    public function getSiteId()
    {
        return $this->siteId;
    }

    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    }

    public function getReturnFormat()
    {
        return $this->returnFormat;
    }

    public function setReturnFormat($returnFormat)
    {
        $this->returnFormat = $returnFormat;
    }

    private function getSoapClient()
    {
        if (empty($this->soapClient)) {
            $this->soapClient = new \SoapClient(self::WSDL);

            $this->setApiKey(\Config::get("scholarshipowl.supercollege.apikey"));
            $this->setSiteUrl(\Config::get("scholarshipowl.supercollege.siteurl"));
            $this->setSiteId(\Config::get("scholarshipowl.supercollege.siteid"));
        }

        return $this->soapClient;
    }

    private function prepareParams($params)
    {
        $defaultParams = $this->getDefaultParams();

        $params = array_merge($defaultParams, $params);
        $params["returnf"] = $this->getReturnFormat();
        $params["apikey"] = $this->getApiKey();
        $params["siteurl"] = $this->getSiteUrl();
        $params["siteid"] = $this->getSiteId();

        return $params;
    }

    private function assertParams($params)
    {
        if (empty($params["apikey"])) {
            throw new \LogicException("SuperCollege - Api key not set");
        }

        if (empty($params["siteurl"])) {
            throw new \LogicException("SuperCollege - Site url not set");
        }

        if (empty($params["siteid"])) {
            throw new \LogicException("SuperCollege - Site id not set");
        }
    }

    private function getDefaultParams()
    {
        $params = array(
            "returnf" => self::RETURN_FORMAT_XML,
            "apikey" => "",
            "siteurl" => "",
            "siteid" => "",
            "userid" => "0",
            "searchrange" => "0",
            "usertype" => "18",
            "restricttype" => "0",
            "needtype" => "0",
            "parenttype" => "0",
            "sex" => "1",
            "citizen" => "1",
            "age" => "0",
            "dobmonth" => "0",
            "dobday" => "0",
            "dobyear" => "0",
            "zipcode" => "0",
            "gpa" => "0",
            "satv" => "0",
            "satm" => "0",
            "satw" => "0",
            "act" => "0",
            "classrank" => "0",
            "gradyear" => "0",
            "maxincome" => "0",
            "major" => "0",
            "career" => "0",
            "interest" => "0",
            "race" => "",
            "religion" => "",
            "disability" => "0",
            "state" => "0",
            "membership" => "",
            "military" => "",
            "athletics" => "",
            "circumstance" => "",
            "collegechoice" => ""
        );

        return $params;
    }

    /**
     * @param Account $account
     * @return array
     */
    public function rawAccountMatches(Account $account)
    {
        $result = array();
        $client = $this->getSoapClient();

        $params = [
            "usertype" => $account->getProfile()->getSchoolLevel() ? $this->mapSchoolLevel($account->getProfile()->getSchoolLevel()->getId()) : "",
            "major" => $account->getProfile()->getDegree() ? $this->mapDegree($account->getProfile()->getDegree()->getId()) : "0",
            "sex" => $this->mapGender($account->getProfile()->getGender()),
            "citizen" => $account->getProfile()->getCitizenship() ? $this->mapCitizenship($account->getProfile()->getCitizenship()->getId()) : "",
            "state" => $account->getProfile()->getState() ? $this->mapState($account->getProfile()->getState()->getId()) : "0",
            "race" => $account->getProfile()->getEthnicity() ? $this->mapEthnicity($account->getProfile()->getEthnicity()->getId()) : "",
            "age" => $account->getProfile()->getAge() ?: "0",
            "dobmonth" => $account->getProfile()->getDateOfBirthMonth() ?: "0",
            "dobday" => $account->getProfile()->getDateOfBirthDay() ?: "0",
            "dobyear" => $account->getProfile()->getDateOfBirthYear() ?: "0",
            "zipcode" => is_numeric($account->getProfile()->getZip()) ? $account->getProfile()->getZip() : "0",
            "gpa" => $account->getProfile()->getGpa()?:"0",
            "gradyear" => $account->getProfile()->getGraduationYear()?:"0",
            "military" => $account->getProfile()->getMilitaryAffiliation()?$this->mapMilitaryAffiliation($account->getProfile()->getMilitaryAffiliation()->getId()):"0",
        ];

        $params = $this->prepareParams($params);
        $this->assertParams($params);

        $response = call_user_func_array(array($client, "findmatches"), array_values($params));
        if (!empty($response)) {
            $response = $response->scholarships;

            if (!empty($response)) {
                foreach ($response->award as $scholarship) {
                    $result[] = (array)$scholarship;
                }
            }
        }

        return $result;
    }

    public function findAndSaveMatches(Account $account)
    {
        $scholarshipMatches = $this->rawAccountMatches($account);

        $foundIds = array();

        foreach ($scholarshipMatches as $scholarshipMatch) {
            /** @var SuperCollegeScholarship $scholarship */
            $scholarship = \EntityManager::getRepository(SuperCollegeScholarship::class)->findOneBy(["uuid" => $scholarshipMatch["S_UUID"]]);

            $details = $this->getDetails($scholarshipMatch["S_UUID"]);

            if (!$scholarship) {
                $scholarship = new SuperCollegeScholarship();

                $scholarship->setUuid($details->S_UUID);
                $scholarship->setUrl($details->WEBSITE);
                $scholarship->setTitle($details->SCHOL_NM);
                $scholarship->setPatron($details->PATRON_NM);
                $scholarship->setAmount($details->AMOUNT);
                $scholarship->setAddress1($details->ADDRESS_1);
                $scholarship->setAddress2($details->ADDRESS_2);
                $scholarship->setAddress3($details->ADDRESS_3);
                $scholarship->setCity($details->CITY);
                $scholarship->setState($details->STATE);
                $scholarship->setZip($details->ZIP);
                $scholarship->setDeadline($details->DEADLINE);
                $scholarship->setHowToApply($details->GET_APP);
                $scholarship->setLevelMin($details->LEVEL_MIN);
                $scholarship->setLevelMax($details->LEVEL_MAX);
                $scholarship->setAwards($details->NUM_AWARDS);
                $scholarship->setRenew($details->RENEW);
                $scholarship->setEligibility($details->SCHOL_ELIG);
                $scholarship->setPurpose($details->SCHOL_PURPOSE);

                \EntityManager::persist($scholarship);
            }else if($scholarship->getUpdatedAt() < Carbon::now()->subHours(4)){
                $scholarship->setUuid($details->S_UUID);
                $scholarship->setUrl($details->WEBSITE);
                $scholarship->setTitle($details->SCHOL_NM);
                $scholarship->setPatron($details->PATRON_NM);
                $scholarship->setAmount($details->AMOUNT);
                $scholarship->setAddress1($details->ADDRESS_1);
                $scholarship->setAddress2($details->ADDRESS_2);
                $scholarship->setAddress3($details->ADDRESS_3);
                $scholarship->setCity($details->CITY);
                $scholarship->setState($details->STATE);
                $scholarship->setZip($details->ZIP);
                $scholarship->setDeadline($details->DEADLINE);
                $scholarship->setHowToApply($details->GET_APP);
                $scholarship->setLevelMin($details->LEVEL_MIN);
                $scholarship->setLevelMax($details->LEVEL_MAX);
                $scholarship->setAwards($details->NUM_AWARDS);
                $scholarship->setRenew($details->RENEW);
                $scholarship->setEligibility($details->SCHOL_ELIG);
                $scholarship->setPurpose($details->SCHOL_PURPOSE);
            }

            $superCollegeScholarshipMatch = \EntityManager::getRepository(SuperCollegeScholarshipMatch::class)->findOneBy(["account" => $account, "superCollegeScholarship" => $scholarship]);
            if(!$superCollegeScholarshipMatch){
                $superCollegeScholarshipMatch = new SuperCollegeScholarshipMatch($account, $scholarship);

                $scholarship->addSuperCollegeScholarshipMatch($superCollegeScholarshipMatch);

                \EntityManager::persist($superCollegeScholarshipMatch);
            }

            $foundIds[] = $superCollegeScholarshipMatch->getSuperCollegeScholarship()->getId();
        }

        if (!empty($foundIds)) {
            $qb = \EntityManager::createQueryBuilder();
            $toRemove = $qb->select("scsm")
                ->from("\App\Entity\SuperCollegeScholarshipMatch", "scsm")
                ->where($qb->expr()->notIn("scsm.superCollegeScholarship", $foundIds))
                ->andWhere("scsm.account = :acc")
                ->setParameter(":acc", $account)
                ->getQuery()
                ->getResult();

            foreach ($toRemove as $item) {
                \EntityManager::remove($item);
            }
        }

        $account->setEligibilityUpdate(Carbon::now());

        \EntityManager::flush();
    }

    public function updateEligibilityForAllAccounts()
    {
        $query = \EntityManager::createQueryBuilder()
            ->select(['a'])
            ->from(Account::class, 'a')
            ->where('a.eligibilityUpdate < :date OR a.eligibilityUpdate IS NULL')
            ->setParameter('date', Carbon::now()->subDays(7))
            ->orderBy('a.accountId', 'DESC')
            ->setMaxResults( 10000 )
            ->getQuery();

        /** @var Account $account */
        foreach ($query->getResult() as $account) {
            if($account->getProfile()->getCompleteness() > 80){
                $this->findAndSaveMatches($account);
            }
        }

        \EntityManager::clear();
    }

    private function mapSchoolLevel($schoolLevelId)
    {
        $mapping = [
            1 => 15,
            2 => 16,
            3 => 17,
            4 => 18,
            5 => 21,
            6 => 22,
            7 => 23,
            8 => 24,
            9 => 30,
            10 => 60
        ];

        return $mapping[$schoolLevelId];
    }

    private function mapGender($gender)
    {
        $mapping = [
            "male" => 1,
            "female" => 2,
        ];

        if(isset($mapping[$gender])){
            return $mapping[$gender];
        }
        return 0;
    }

    private function mapCitizenship($citizenshipId)
    {
        $mapping = [
            1 => 1,
            2 => 1,
            3 => 1,
        ];

        if(!$citizenshipId){
            return 1;
        }
        return $mapping[$citizenshipId];
    }

    private function mapDegree($degreeId)
    {
        $mapping = [
            1 => "1.",
            2 => "4.",
            3 => "5.",
            4 => "26.",
            5 => "52.",
            6 => "9.",
            7 => "11.",
            8 => "46.",
            9 => "13.",
            10 => "14.",
            11 => "23.",
            12 => "19.",
            13 => "16.",
            14 => "51.",
            15 => "54.",
            16 => "22.",
            17 => "24.",
            18 => "25.",
            19 => "27.",
            20 => "47.",
            21 => "29.",
            22 => "30.",
            23 => "3.",
            24 => "31.",
            25 => "12.",
            26 => "38.",
            27 => "40.",
            28 => "48.",
            29 => "42.",
            30 => "44.",
            31 => "43.",
            32 => "45.",
            33 => "21.",
            34 => "39.",
            35 => "49.",
            36 => "50.",
            37 => "0",
        ];

        return $mapping[$degreeId];
    }

    private function mapState($stateId)
    {
        if ($stateId < 40) {
            return $stateId;
        }

        $mapping = [
            40 => 58,
            41 => 40,
            42 => 41,
            43 => 42,
            44 => 43,
            45 => 44,
            46 => 45,
            47 => 46,
            48 => 47,
            49 => 48,
            50 => 49,
            51 => 50,
            52 => 51,
        ];

        return $mapping[$stateId];
    }

    private function mapEthnicity($ethnicityId)
    {
        $mapping = [
            1 => "33",
            2 => "5",
            3 => "14",
            4 => "4,20",
            5 => "1,9",
            6 => "",
        ];

        return $mapping[$ethnicityId];
    }

    private function mapMilitaryAffiliation($militaryAffiliationId)
    {
        $mapping = [
            "1" => "1",
            "2" => "2",
            "3" => "3",
            "4" => "4",
            "5" => "5",
            "6" => "6",
            "7" => "7",
            "8" => "48 ",
            "9" => "11,20,16,35,26,34,33,2,10,12,14,18,8,39,40",
            "11" => "38,44,45,46",
            "14" => "50,51",
            "15" => "31,24,32",
            "16" => "47,25",
            "17" => "29",
            "18" => "28",
            "19" => "30",
        ];

        if (isset($mapping[$militaryAffiliationId])) {
            return $mapping[$militaryAffiliationId];
        }

        return "";
    }
}
