<?php

namespace App\Submissions;

use App\Entity\Account;
use App\Entity\Profile;
use App\Entity\Marketing\Submission;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BirdDogSubmission extends AbstractSubmission
{
    const BIRDDOG_RESERVE_PRIOR_SERV_OFFER = 1229681;
    const BIRDDOG_NUPOC_OFFER = 1235060;
    const BIRDDOG_FEMALE_OFFER = 1235064;
    const BIRDDOG_HISPANIC_OFFER = 1235062;
    const BIRDDOG_ASIAN_OFFER = 1235063;
    const BIRDDOG_AA_OFFER = 1235061;
    const BIRDDOG_AA_GEN_OFFICER_OFFER= 1239649;

    protected $degreeMap = [
        4 => 11,
        5 => 12,
        6 => 13,
        7 => 14,
        8 => 15,
        9 => 16,
        10 => 16,
    ];

    protected $citizenshipMap = [
        1 => "C",
        2 => "R",
    ];

    public function submissionSend($batch)
    {
        $leadType = 'test';
        if (is_production()) {
            $leadType = 'live';
        }

        $submissions = $this->ss->getSubmissionsAccountsByNameAndStatus(Submission::NAME_BIRDDOG,
            Submission::STATUS_PENDING, $batch);

        /** @var Submission $submission */
        foreach ($submissions as $submission) {
            $submissionId = $submission->getSubmissionId();

            try {
                /** @var Account $account */
                $account = $submission->getAccount();

            if (!$account->isUSA()) {
                $this->ss->updateErrorSubmission($submissionId, 'Unsupported country.');
                continue;
            }

                $params = json_decode($submission->getParams(), true);
                $profile = $account->getProfile();
                if(isset($params['bdversion']) && $params['bdversion'] == 2){
                    $data = $this->getNewBirdDogData($params, $profile, $account, $leadType);
                }else{
                    $data = $this->getCommonBirdDogData($params, $account, $leadType, $submissionId);
                }

                if($data == false){
                    continue;
                }

                $response = $this->send($data);

                if ($this->hasErrors()) {
                    $errorString = implode(",", $this->getErrors());
                    \CoregLogger::error($errorString);
                    $this->ss->updateErrorValidationSubmission($submissionId, $errorString);
                } else {
                    $this->ss->updateSuccessSubmission($submissionId, $response);
                }
            }
            catch (EntityNotFoundException $notFound){
                \Log::info("Account not found while sending submission [$submissionId]");
                $this->ss->updateErrorSubmission($submissionId, 'Account was deleted');
            }
            catch (\Throwable $exc) {
                \CoregLogger::error($exc);
                $this->ss->updateErrorSubmission($submissionId, $exc->getMessage());
            }
        }
    }

    public function send($params = null)
    {
        $this->response = "";
        $this->rawResponse = "";
        $this->errors = array();

        $params = $this->onRequest($params);

        $url = $this->getUrl() . "?" . http_build_query($params);

        $this->curl->post($url);

        $this->response = $this->curl->response;
        $this->rawResponse = $this->curl->rawResponse;

        if ($this->curl->error) {
            throw new \RuntimeException($this->curl->curlErrorMessage, $this->curl->curlErrorCode);
        }

        return $this->onResponse();
    }

    public function onRequest($params = array())
    {
        $result = $this->getAuth();

        foreach ($params as $name => $value) {
            $result[$name] = $value;
        }

        return $result;
    }

    public function onResponse()
    {
        $response = $this->getResponse();
        $responseMsgArray = explode("\r\n\r\n", $response);
        if(strpos($response, 'FAIL') !== false){
            $this->errors[] = $responseMsgArray[0];
        }

        return $responseMsgArray[0];

    }

    /**
     * @param $params
     * @param $account
     * @param $leadType
     * @param $submissionId
     *
     * @return array|false
     */
    private function getCommonBirdDogData($params, $account, $leadType, $submissionId){
        $resolver = new OptionsResolver();
        $resolver->setRequired(['offer_id', 'oid']);
        $resolver->setDefined(['sms']);
        $resolver->setDefault('sms',0);
        $resolverResult = $resolver->resolve($params);

        $offerId = $resolverResult['offer_id'];
        $oid = $resolverResult['oid'];

        /** @var Profile $profile */
        $profile = $account->getProfile();

        $degre = 0;
        if (isset($this->degreeMap[$profile->getSchoolLevel()->getId()])) {
            $degre = $this->degreeMap[$profile->getSchoolLevel()->getId()];
        }

        $data = [
            "1"           => $offerId,
            "3"           => $profile->getFirstName(),
            "5"           => $profile->getLastName(),
            '26'          => $resolverResult['sms'] ? 'Y' : 'N',
            "7"           => $profile->getFullAddress(),
            "City"        => $profile->getCity(),
            "State"       => $profile->getState()->getAbbreviation(),
            "9"           => $profile->getZip(),
            "37"          => $account->getEmail(),
            "11"          => str_replace("+", "", $profile->getPhone()),
            "14"          => $profile->getDateOfBirthMonth() . '/' . $profile->getDateOfBirthDay() . '/' . $profile->getDateOfBirthYear(),
            "18"          => 'y',
            'Ethnicity'   => 'y',
            '19'          => $degre,
            'ALID'        => 1669,
            'OID'         => $oid,
            'BDMLeadType' => $leadType
        ];

        if (in_array($offerId, [1244628, 1244624, 1244630, 1244625])) {
            $gpaValue = $profile->getGpa();

            if ($gpaValue == "N/A") {
                $this->ss->updateErrorSubmission($submissionId, 'Wrong GPA - N/A');
                return false;
            }

            if (in_array($offerId, [1244624, 1244625])) {
                $gpaValue = number_format($gpaValue, 2);
            }

            $data[25] = $gpaValue;
        }

        if ($offerId == self::BIRDDOG_RESERVE_PRIOR_SERV_OFFER) {
            $data['prior_service'] = 'y';
        }

        return $data;
    }

    /**
     * @param $params
     * @param $profile
     * @param $account
     * @param $leadType
     *
     * @return array
     */
    private function getNewBirdDogData(
        $params,
        $profile,
        $account,
        $leadType
    ): array
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(['bdversion', 'oid']);
        $resolverResult = $resolver->resolve($params);
        $oid = $resolverResult['oid'];
        $degre = 0;
        if (isset($this->degreeMap[$profile->getSchoolLevel()->getId()])) {
            $degre = $this->degreeMap[$profile->getSchoolLevel()->getId()];
        }
        $data = [
            "alid"        => 1669,
            '001'         => 'BIRDDOG',
            '002'         => '02',
            '050'         => 'KQ74',
            '151'         => 'IR',
            '178'         => 'R',
            '176'         => 'www.birddogmedia.com',
            '003'         => date("m/d/y"),
            "023"         => $profile->getFirstName(),
            "025"         => $profile->getLastName(),
            "037"         => $profile->getFullAddress(),
            "040"         => $profile->getCity(),
            "041"         => $profile->getState()->getAbbreviation(),
            "042"         => $profile->getZip(),
            "032"         => $account->getEmail(),
            "029"         => preg_replace('/[^0-9.]+/', '', $profile->getPhone()),
            "028"         => $profile->getDateOfBirthMonth() . '/'. $profile->getDateOfBirthDay() . '/'. $profile->getDateOfBirthYear(),
            '074'         => $degre,
            '106'         => $this->citizenshipMap[$profile->getCitizenship()->getId()],
            "checkbox"    => 'y',
            'OID'         => $oid,
            'BDMLeadType' => $leadType
        ];

        return $data;
    }
}
