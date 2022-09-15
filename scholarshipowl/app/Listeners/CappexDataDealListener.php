<?php namespace App\Listeners;

use App\Entity\Account;
use App\Entity\College;
use App\Entity\Marketing\CoregPlugin;
use App\Entity\Marketing\Submission;
use App\Entity\Marketing\SubmissionSources;
use App\Entity\Profile;
use App\Events\Account\UpdateAccountEvent;
use App\Facades\EntityManager;
use App\Services\Marketing\SubmissionService;
use App\Submissions\CappexDataDealSubmission;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;

class CappexDataDealListener implements ShouldQueue
{
    const AFID = 434645;

    const ENDPOINT = "http://cappex.linktrustleadgen.com/Lead/%cid%/SimplePost";
    /**
     * @var SubmissionService
     */
    protected $ss;

    public function __construct(SubmissionService $ss)
    {
        $this->ss = $ss;
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(UpdateAccountEvent::class, static::class . '@onAccountUpdate');
    }

    public function onAccountUpdate(UpdateAccountEvent $event)
    {
        //we should fire only after users done with register flow
        if (strpos($event->getReferer(), 'register3') === false) {
           return;
        }

        $account = $event->getAccount();
        $profile = $event->getAccount()->getProfile();
        $accountId = $account->getAccountId();
        //Cappex data deal only for US -high school students
        if (!$account->isUSA() && $profile->getSchoolLevel()->getId() > 4) {
            return;
        }
        //check if user already has submission
        $coreg = $this->getCoregPlugin();
        if (is_null($coreg) || EntityManager::getRepository(Submission::class)->findOneBy(['account' => $account, 'coregPlugin' => $coreg->getId()])) {
            return;
        }

        if ($profile->getGender() == Profile::GENDER_OTHER) {
            return;
        }

        $rule = $this->againstRule($account);

        if ($rule) {
            $history = EntityManager::getRepository(\App\Entity\Log\LoginHistory::class);
            $loginHistory = $history->findBy(
                ['account' => $accountId],
                ['loginHistoryId' => 'DESC']
            );
            $state = '';
            if ($profile->getState()) {
                $state = $profile->getState()->getAbbreviation();
            }

            $collegeId = (int)$rule[0]['iped'][0];


            if (!isset(CappexDataDealSubmission::endpoints()[$collegeId])) {
                \Log::error("CappexDataDealSubmission. Endpoint for college $collegeId doesn't exist.");
                return;
            }

            $collegeData = CappexDataDealSubmission::endpoints()[$collegeId];
            $cid = $collegeData['cid'];
            $collegeEndpoint = str_replace('%cid%', $cid, self::ENDPOINT);
            $row = [
                'AFID' => self::AFID,
                'CID' => $cid,
                'endpoint_url' => $collegeEndpoint,
                'Test' => is_production() ? 'no' : 'yes',
                'email_address' => $account->getEmail(),
                'f_name' => $profile->getFirstName(),
                'l_name' => $profile->getLastName(),
                'gender' => strtolower($profile->getGender()),
                'b_year' => $profile->getDateOfBirthYear(),
                'b_month' => $profile->getDateOfBirthMonth(),
                'b_date' => $profile->getDateOfBirthDay(),
                'address' => $profile->getAddress(),
                'zip_code' => $profile->getZip(),
                'city_name' => $profile->getCity(),
                'state_name' => $state,
                'country_id' => 'US',
                'hs_grad_month' => $profile->getHighschoolGraduationMonth(),
                'hs_grad_year' => $profile->getHighschoolGraduationYear(),
                'nonweighted_hs_gpa' => $profile->getGpa(),
                'college_considering' => $collegeId,
                'studentType' => 1
            ];
            try {
                $ip = isset($loginHistory[0]) ? $loginHistory[0]->getIpAddress() : '';
                $submission = $this->ss->addSubmissions(
                    [
                        Submission::NAME_CAPPEXDATADEAL => [
                            'checked' => 1,
                            'extra' => $row,
                            'id' => $coreg->getId()
                        ]
                    ], $accountId, $ip, SubmissionSources::SYSTEM
                );
            } catch (\Exception $e) {
                \Log::error("Fail on CappexDataDeal submissions stores: " . $e->getMessage());
            }
        }
    }

    /**
     * @return CoregPlugin | null
     */
    protected function getCoregPlugin()
    {
        /**
         * @var CoregPlugin $coregPlugin
         */
        $coregPlugin = EntityManager::getRepository(CoregPlugin::class)->createQueryBuilder('cp')
            ->where('cp.name = :name')
            ->andWhere('cp.justCollect != 1')
            ->setParameter(':name', Submission::NAME_CAPPEXDATADEAL)
            ->setCacheable(true)
            ->getQuery()
            ->getResult();

        return empty($coregPlugin) ? null : $coregPlugin[0];
    }

    /**
     * @param Account $accont
     * @return array
     */
    public function againstRule(Account $accont, $order = false)
    {
        $profile = $accont->getProfile();
        $rules = $this->getRules();
        $suitableRules = [];
        $gradYear = $profile->getHighschoolGraduationYear();
        foreach ($rules as $k => $rule) {
            if (
                $this->stateSuit($profile, $rule['states']) &&
                $this->gradYearSuit($gradYear, $rule['grad_year']) &&
                $this->gpaSuit($profile, $rule['gpa']) &&
                $this->genderSuit($profile, $rule['gender']) &&
                $this->degreeSuit($profile, $rule['degree']) &&
                $this->ipedSuit($profile, $rule['iped'])
            ) {
                $suitableRules[] = $rule;
            }
        }

        if ($order) {
            usort($suitableRules, function ($a, $b) {
                return $a['price'] <=> $b['price'];
            });
            usort($suitableRules, function ($a, $b) {
                return $a['cap'] <=> $b['cap'];
            });
        }

        return $suitableRules;
    }

    /**
     * @param array $userState
     * @param \App\Entity\Profile $profile
     * @param $item
     * @return bool
     */
    public function stateSuit(\App\Entity\Profile $profile, $item): bool
    {
        $userState[] = 'Nationwide';
        if (!is_null($profile->getState())) {
            $userState[] = $profile->getState()->getAbbreviation();
        }
        return count(array_intersect($userState, $item)) > 0;
    }

    /**
     * @param int $gradYear
     * @param $item
     * @return bool
     */
    public function gradYearSuit($gradYear, $item): bool
    {
        return in_array($gradYear, $item);
    }

    /**
     * @param \App\Entity\Profile $profile
     * @param $item
     * @return bool
     */
    public function gpaSuit(\App\Entity\Profile $profile, $item): bool
    {
        return $profile->getGpa() >= $item;
    }

    /**
     * @param \App\Entity\Profile $profile
     * @param $item
     * @return bool
     */
    public function genderSuit(\App\Entity\Profile $profile, $item): bool
    {
        return $item == "N/A" || strtolower($profile->getGender()) == strtolower($item);
    }

    /**
     * @param \App\Entity\Profile $profile
     * @param $item
     * @return bool
     */
    public function degreeSuit(\App\Entity\Profile $profile, $item): bool
    {
        if ($item == "N/A") {
            return  true;
        }
        $major = array_unique(explode(',', $item));
        $degreeId = '';
        if (!is_null($profile->getDegree())) {
            $degreeId = $profile->getDegree()->getId();
        }
        return in_array($degreeId, $major);
    }

    /**
     * @param \App\Entity\Profile $profile
     * @param $item
     * @return bool
     */
    public function ipedSuit(\App\Entity\Profile $profile, $item)
    {
        /**
         * @var College[] $profileUniversities
         */
        $profileUniversities = \EntityManager::getRepository(College::class)->findBy(['canonicalName' => $profile->getUniversities()]);
        $ipedCodeList = [];
        foreach ($profileUniversities as $university) {
            if (!empty($university->getIpedCode())) {
                $ipedCodeList[] = $university->getIpedCode();
            }
        }

        return count(array_intersect($ipedCodeList, $item)) > 0;
    }

    protected function convertDegreeToId($degree)
    {
        $degreId = 1;
        return $degreId;

    }

    protected function getRules()
    {
        return [
            0 =>
                [
                    'price' => '0.59',
                    'cap' => 167,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.7',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '188429',
                        ],
                ],
            1 =>
                [
                    'price' => '0.86',
                    'cap' => 270,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '210669',
                        ],
                ],
            2 =>
                [
                    'price' => '1.00',
                    'cap' => 87,
                    'states' =>
                        [
                            0 => 'WI',
                            1 => 'IA',
                            2 => 'IL',
                            3 => 'MI',
                            4 => 'MN',
                            5 => 'IN',
                            6 => 'TX',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.4',
                    'gender' => 'Female',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '238193',
                        ],
                ],
            3 =>
                [
                    'price' => '1.25',
                    'cap' => 1691,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '2.0',
                    'gender' => 'N/A',
                    'degree' => '222,222,20,20,329,329,1654,1654,1653,1653,354,354,1650,1650,427,427,195,195,469,469,471,471,511,511,1647,1647,21,21,550,550,551,551,570,570,194,194,1644,1644,582,582,1648,1648,37,37,589,589,590,590,182,182,694,694,24,24,696,696,330,330,698,698,25,25,1645,1645,26,26,833,833,839,839,188,188,854,854,877,877,1149,1149,972,972,1003,1003,1004,1004,1005,1005,1006,1006,1649,1649,1008,1008,1010,1010,1011,1011,1646,1646,1013,1013,31,31,1652,1652,33,33,1163,1163,1190,1190,35,35,1419,1419,1345,1345,1352,1352,1354,1354,1422,1422,1423,1423,1425,1425,1651,1651,',
                    'iped' =>
                        [
                            0 => '188854',
                        ],
                ],
            4 =>
                [
                    'price' => '0.65',
                    'cap' => 5095,
                    'states' =>
                        [
                            0 => 'TX',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '0.1',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '222831',
                        ],
                ],
            5 =>
                [
                    'price' => '0.85',
                    'cap' => 914,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.7',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '31089',
                        ],
                ],
            6 =>
                [
                    'price' => '0.30',
                    'cap' => 2645,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '104151',
                        ],
                ],
            7 =>
                [
                    'price' => '0.75',
                    'cap' => 132,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'IA',
                            2 => 'IL',
                            3 => 'IN',
                            4 => 'MI',
                            5 => 'MO',
                            6 => 'TN',
                            7 => 'TX',
                            8 => 'WI',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '143118',
                        ],
                ],
            8 =>
                [
                    'price' => '0.60',
                    'cap' => 2009,
                    'states' =>
                        [
                            0 => 'AZ',
                            1 => 'CA',
                            2 => 'CO',
                            3 => 'HI',
                            4 => 'OR',
                            5 => 'TX',
                            6 => 'WA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '109785',
                        ],
                ],
            9 =>
                [
                    'price' => '2.00',
                    'cap' => 63,
                    'states' =>
                        [
                            0 => 'OH',
                            1 => 'PA',
                            2 => 'NY',
                            3 => 'MI',
                            4 => 'IL',
                            5 => 'FL',
                            6 => 'KY',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '201195',
                        ],
                ],
            10 =>
                [
                    'price' => '1.25',
                    'cap' => 56,
                    'states' =>
                        [
                            0 => 'NJ',
                            1 => 'NY',
                            2 => 'PA',
                            3 => 'RI',
                            4 => 'CT',
                            5 => 'MA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.2',
                    'gender' => 'female',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '164632',
                        ],
                ],
            11 =>
                [
                    'price' => '1.00',
                    'cap' => 76,
                    'states' =>
                        [
                            0 => 'CO',
                            1 => 'IA',
                            2 => 'IL',
                            3 => 'MN',
                            4 => 'ND',
                            5 => 'SD',
                            6 => 'WI',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.75',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '173160',
                        ],
                ],
            12 =>
                [
                    'price' => '1.07',
                    'cap' => 157,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.7',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '150145',
                        ],
                ],
            13 =>
                [
                    'price' => '0.60',
                    'cap' => 24732,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '110097',
                        ],
                ],
            14 =>
                [
                    'price' => '0.20',
                    'cap' => 3026,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '3.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '164988',
                        ],
                ],
            15 =>
                [
                    'price' => '1.00',
                    'cap' => 52,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.85',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '139199',
                        ],
                ],
            16 =>
                [
                    'price' => '1.50',
                    'cap' => 186,
                    'states' =>
                        [
                            0 => 'AL',
                            1 => 'FL',
                            2 => 'GA',
                            3 => 'SC',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '139205',
                        ],
                ],
            17 =>
                [
                    'price' => '1.25',
                    'cap' => 110,
                    'states' =>
                        [
                            0 => 'AZ',
                            1 => 'CO',
                            2 => 'IA',
                            3 => 'IL',
                            4 => 'LA',
                            5 => 'MN',
                            6 => 'MO',
                            7 => 'ND',
                            8 => 'NE',
                            9 => 'NV',
                            10 => 'SD',
                            11 => 'TX',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '152992',
                        ],
                ],
            18 =>
                [
                    'price' => '2.00',
                    'cap' => 92,
                    'states' =>
                        [
                            0 => 'IA',
                            1 => 'KS',
                            2 => 'MN',
                            3 => 'MO',
                            4 => 'NE',
                            5 => 'SD',
                            6 => 'IL',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '180878',
                        ],
                ],
            19 =>
                [
                    'price' => '0.45',
                    'cap' => 335,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '211291',
                        ],
                ],
            20 =>
                [
                    'price' => '1.11',
                    'cap' => 67,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'CT',
                            2 => 'FL',
                            3 => 'MA',
                            4 => 'MD',
                            5 => 'NJ',
                            6 => 'NY',
                            7 => 'OH',
                            8 => 'PA',
                            9 => 'RI',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '183910',
                        ],
                ],
            21 =>
                [
                    'price' => '0.60',
                    'cap' => 1886,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '110361',
                        ],
                ],
            22 =>
                [
                    'price' => '1.20',
                    'cap' => 1005,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.8',
                    'gender' => 'N/A',
                    'degree' => '1451,320,1452,321,19,16,17,662,853,18,1453,222,222,20,20,329,329,1654,1654,1653,1653,354,354,1650,1650,427,427,195,195,469,469,471,471,511,511,1647,1647,21,21,550,550,551,551,570,570,194,194,1644,1644,582,582,1648,1648,37,37,589,589,590,590,182,182,694,694,24,24,696,696,330,330,698,698,25,25,1645,1645,26,26,833,833,839,839,188,188,854,854,877,877,1149,1149,972,972,1003,1003,1004,1004,1005,1005,1006,1006,1649,1649,1008,1008,1010,1010,1011,1011,1646,1646,1013,1013,31,31,1652,1652,33,33,1163,1163,1190,1190,35,35,1419,1419,1345,1345,1352,1352,1354,1354,1422,1422,1423,1423,1425,1425,1651,1651,',
                    'iped' =>
                        [
                            0 => '110370',
                        ],
                ],
            23 =>
                [
                    'price' => '0.40',
                    'cap' => 3163,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '173258',
                        ],
                ],
            24 =>
                [
                    'price' => '1.20',
                    'cap' => 13,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.25',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '238476',
                        ],
                ],
            25 =>
                [
                    'price' => '1.75',
                    'cap' => 37,
                    'states' =>
                        [
                            0 => 'AR',
                            1 => 'GA',
                            2 => 'LA',
                            3 => 'MS',
                            4 => 'TN',
                            5 => 'TX',
                            6 => 'CO',
                            7 => 'MO',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '158477',
                        ],
                ],
            26 =>
                [
                    'price' => '0.75',
                    'cap' => 223,
                    'states' =>
                        [
                            0 => 'AK',
                            1 => 'CA',
                            2 => 'HI',
                            3 => 'CO',
                            4 => 'NV',
                            5 => 'OR',
                            6 => 'WA',
                            7 => 'AZ',
                            8 => 'ID',
                            9 => 'IL',
                            10 => 'MT',
                            11 => 'TX',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '234827',
                        ],
                ],
            27 =>
                [
                    'price' => '0.90',
                    'cap' => 231,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '3.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '156408',
                        ],
                ],
            28 =>
                [
                    'price' => '1.35',
                    'cap' => 414,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'HI',
                            2 => 'TX',
                            3 => 'NM',
                            4 => 'NV',
                            5 => 'OR',
                            6 => 'WA',
                            7 => 'AZ',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '141486',
                        ],
                ],
            29 =>
                [
                    'price' => '1.25',
                    'cap' => 622,
                    'states' =>
                        [
                            0 => 'CT',
                            1 => 'DC',
                            2 => 'DE',
                            3 => 'MA',
                            4 => 'MD',
                            5 => 'ME',
                            6 => 'NH',
                            7 => 'NJ',
                            8 => 'NY',
                            9 => 'PA',
                            10 => 'RI',
                            11 => 'VA',
                            12 => 'VT',
                            13 => 'CO',
                            14 => 'NC',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '230852',
                        ],
                ],
            30 =>
                [
                    'price' => '1.50',
                    'cap' => 806,
                    'states' =>
                        [
                            0 => 'AL',
                            1 => 'CA',
                            2 => 'CO',
                            3 => 'FL',
                            4 => 'GA',
                            5 => 'NY',
                            6 => 'TN',
                            7 => 'DC',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '111966',
                        ],
                ],
            31 =>
                [
                    'price' => '0.90',
                    'cap' => 446,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'CO',
                            2 => 'CT',
                            3 => 'DC',
                            4 => 'FL',
                            5 => 'GA',
                            6 => 'KY',
                            7 => 'MA',
                            8 => 'MD',
                            9 => 'MI',
                            10 => 'NJ',
                            11 => 'NY',
                            12 => 'OH',
                            13 => 'PA',
                            14 => 'TX',
                            15 => 'VA',
                            16 => 'WV',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '211556',
                        ],
                ],
            32 =>
                [
                    'price' => '0.90',
                    'cap' => 301,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => '',
                            2 => 'CO',
                            3 => 'CT',
                            4 => 'DC',
                            5 => 'IL',
                            6 => 'MA',
                            7 => 'MD',
                            8 => 'ME',
                            9 => 'NH',
                            10 => 'NJ',
                            11 => 'NM',
                            12 => 'NY',
                            13 => 'OR',
                            14 => 'PA',
                            15 => 'TX',
                            16 => 'VA',
                            17 => 'WA',
                            18 => 'FL',
                            19 => 'GA',
                            20 => 'NC',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '165334',
                        ],
                ],
            33 =>
                [
                    'price' => '1.00',
                    'cap' => 48,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'DC',
                            2 => 'FL',
                            3 => 'GA',
                            4 => 'IL',
                            5 => 'IN',
                            6 => 'MD',
                            7 => 'MI',
                            8 => 'NJ',
                            9 => 'NY',
                            10 => 'OH',
                            11 => 'PA',
                            12 => 'TN',
                            13 => 'TX',
                            14 => 'VA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '202046',
                        ],
                ],
            34 =>
                [
                    'price' => '0.15',
                    'cap' => 2300,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '218724',
                        ],
                ],
            35 =>
                [
                    'price' => '0.50',
                    'cap' => 69137,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '3.4',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '190099',
                        ],
                ],
            36 =>
                [
                    'price' => '1.10',
                    'cap' => 1100,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'FL',
                            2 => 'IL',
                            3 => 'NJ',
                            4 => 'PA',
                            5 => 'RI',
                            6 => 'TX',
                            7 => 'VA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.75',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '193399',
                        ],
                ],
            37 =>
                [
                    'price' => '1.00',
                    'cap' => 134,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.2',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '126669',
                        ],
                ],
            38 =>
                [
                    'price' => '0.80',
                    'cap' => 583,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'female',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '217934',
                        ],
                ],
            39 =>
                [
                    'price' => '0.50',
                    'cap' => 13894,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '144281',
                        ],
                ],
            40 =>
                [
                    'price' => '1.50',
                    'cap' => 70,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '202170',
                        ],
                ],
            41 =>
                [
                    'price' => '0.75',
                    'cap' => 74,
                    'states' =>
                        [
                            0 => 'AZ',
                            1 => 'CA',
                            2 => 'CO',
                            3 => 'FL',
                            4 => 'HI',
                            5 => 'IL',
                            6 => 'MA',
                            7 => 'MI',
                            8 => 'NV',
                            9 => 'PA',
                            10 => 'TX',
                            11 => 'UT',
                            12 => 'VA',
                            13 => 'WA',
                            14 => 'WI',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.8',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '112075',
                        ],
                ],
            42 =>
                [
                    'price' => '0.50',
                    'cap' => 2283,
                    'states' =>
                        [
                            0 => 'AZ',
                            1 => 'CA',
                            2 => 'CO',
                            3 => 'IA',
                            4 => 'IL',
                            5 => 'MN',
                            6 => 'MO',
                            7 => 'OR',
                            8 => 'TX',
                            9 => 'WA',
                            10 => 'WI',
                            11 => 'SD',
                            12 => 'KS',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '2.75',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '153162',
                        ],
                ],
            43 =>
                [
                    'price' => '3.00',
                    'cap' => 228,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => '222,222,20,20,329,329,1654,1654,1653,1653,354,354,1650,1650,427,427,195,195,469,469,471,471,511,511,1647,1647,21,21,550,550,551,551,570,570,194,194,1644,1644,582,582,1648,1648,37,37,589,589,590,590,182,182,694,694,24,24,696,696,330,330,698,698,25,25,1645,1645,26,26,833,833,839,839,188,188,854,854,877,877,1149,1149,972,972,1003,1003,1004,1004,1005,1005,1006,1006,1649,1649,1008,1008,1010,1010,1011,1011,1646,1646,1013,1013,31,31,1652,1652,33,33,1163,1163,1190,1190,35,35,1419,1419,1345,1345,1352,1352,1354,1354,1422,1422,1423,1423,1425,1425,1651,1651,',
                    'iped' =>
                        [
                            0 => '235024',
                        ],
                ],
            44 =>
                [
                    'price' => '1.10',
                    'cap' => 26,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'FL',
                            2 => 'NJ',
                            3 => 'NY',
                            4 => 'PA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '190549',
                        ],
                ],
            45 =>
                [
                    'price' => '1.00',
                    'cap' => 20,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'CT',
                            2 => 'FL',
                            3 => 'NJ',
                            4 => 'NY',
                            5 => 'PA',
                            6 => 'TX',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '190725',
                        ],
                ],
            46 =>
                [
                    'price' => '0.90',
                    'cap' => 184,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '150400',
                        ],
                ],
            47 =>
                [
                    'price' => '0.80',
                    'cap' => 2203,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'CT',
                            2 => 'DC',
                            3 => 'FL',
                            4 => 'GA',
                            5 => 'IL',
                            6 => 'MA',
                            7 => 'MD',
                            8 => 'NJ',
                            9 => 'NY',
                            10 => 'PA',
                            11 => 'TN',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '184348',
                        ],
                ],
            48 =>
                [
                    'price' => '1.50',
                    'cap' => 582,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '150455',
                        ],
                ],
            49 =>
                [
                    'price' => '1.05',
                    'cap' => 163,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '129215',
                        ],
                ],
            50 =>
                [
                    'price' => '1.00',
                    'cap' => 179,
                    'states' =>
                        [
                            0 => 'IL',
                            1 => 'IN',
                            2 => 'MO',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '144892',
                        ],
                ],
            51 =>
                [
                    'price' => '0.98',
                    'cap' => 415,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '212133',
                        ],
                ],
            52 =>
                [
                    'price' => '1.00',
                    'cap' => 178,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'CO',
                            2 => 'HI',
                            3 => 'IL',
                            4 => 'IN',
                            5 => 'MN',
                            6 => 'OR',
                            7 => 'TX',
                            8 => 'WI',
                            9 => 'AL',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.8',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '235097',
                        ],
                ],
            53 =>
                [
                    'price' => '0.60',
                    'cap' => 763,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '133492',
                        ],
                ],
            54 =>
                [
                    'price' => '0.64',
                    'cap' => 888,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'FL',
                            2 => 'MD',
                            3 => 'NJ',
                            4 => 'NY',
                            5 => 'OH',
                            6 => 'PA',
                            7 => 'TX',
                            8 => 'DE',
                            9 => 'VA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '212197',
                        ],
                ],
            55 =>
                [
                    'price' => '0.70',
                    'cap' => 526,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '165662',
                        ],
                ],
            56 =>
                [
                    'price' => '0.50',
                    'cap' => 8173,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '235167',
                        ],
                ],
            57 =>
                [
                    'price' => '1.15',
                    'cap' => 62,
                    'states' =>
                        [
                            0 => 'MD',
                            1 => 'NC',
                            2 => 'PA',
                            3 => 'SC',
                            4 => 'VA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '232089',
                        ],
                ],
            58 =>
                [
                    'price' => '0.67',
                    'cap' => 139,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '165802',
                        ],
                ],
            59 =>
                [
                    'price' => '0.40',
                    'cap' => 7957,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '3.2',
                    'gender' => 'N/A',
                    'degree' => '102,256,318,1487,103,1489,428,1479,105,1480,104,448,106,494,495,502,512,107,1483,1485,1488,649,650,651,108,116,668,713,741,744,109,1481,926,111,112,1486,113,980,1026,114,1061,1065,1484,1122,123,1478,115,1322,1328,1482,1350,1372,1429,237,317,319,1491,349,375,160,1493,446,447,492,493,496,503,507,513,586,587,624,627,628,633,634,646,1495,652,653,1497,655,654,661,663,764,765,785,830,842,844,845,851,1490,897,927,943,944,945,974,981,982,1498,1041,1059,1496,1123,1161,1215,1216,1246,1494,1296,1323,1348,1428,1492,269,287,311,312,1520,489,1521,742,1522,938,1523,940,129,941,131,1315,1364,1602,1601,453,457,1603,463,484,488,199,572,620,664,677,688,712,753,783,837,1120,1147,145,1199,1200,1201,1600,1260,1287,192,202,490,491,82,83,196,498,499,500,80,81,501,1468,504,505,509,553,554,555,556,1465,848,187,1467,1466,1325,1326,191,1431,1440',
                    'iped' =>
                        [
                            0 => '133881',
                        ],
                ],
            60 =>
                [
                    'price' => '2.00',
                    'cap' => 409,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '451680',
                        ],
                ],
            61 =>
                [
                    'price' => '0.53',
                    'cap' => 2511,
                    'states' =>
                        [
                            0 => 'IL',
                            1 => 'IN',
                            2 => 'KS',
                            3 => 'MO',
                            4 => 'OK',
                            5 => 'TN',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '177418',
                        ],
                ],
            62 =>
                [
                    'price' => '0.75',
                    'cap' => 464,
                    'states' =>
                        [
                            0 => 'DC',
                            1 => 'DE',
                            2 => 'MD',
                            3 => 'NJ',
                            4 => 'NY',
                            5 => 'OH',
                            6 => 'PA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '212601',
                        ],
                ],
            63 =>
                [
                    'price' => '0.36',
                    'cap' => 562,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '139861',
                        ],
                ],
            64 =>
                [
                    'price' => '1.20',
                    'cap' => 1135,
                    'states' =>
                        [
                            0 => 'CT',
                            1 => 'DC',
                            2 => 'DE',
                            3 => 'MA',
                            4 => 'MD',
                            5 => 'NJ',
                            6 => 'NY',
                            7 => 'PA',
                            8 => 'RI',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '184773',
                        ],
                ],
            65 =>
                [
                    'price' => '0.70',
                    'cap' => 61,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '3.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '212674',
                        ],
                ],
            66 =>
                [
                    'price' => '2.50',
                    'cap' => 324,
                    'states' =>
                        [
                            0 => 'DC',
                            1 => 'DE',
                            2 => 'MD',
                            3 => 'NJ',
                            4 => 'NY',
                            5 => 'PA',
                            6 => 'VA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.25',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '130989',
                        ],
                ],
            67 =>
                [
                    'price' => '0.80',
                    'cap' => 595,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.7',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '165936',
                        ],
                ],
            68 =>
                [
                    'price' => '1.11',
                    'cap' => 241,
                    'states' =>
                        [
                            0 => 'IA',
                            1 => 'IL',
                            2 => 'MO',
                            3 => 'NE',
                            4 => 'TX',
                            5 => 'WI',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '153375',
                        ],
                ],
            69 =>
                [
                    'price' => '1.33',
                    'cap' => 1623,
                    'states' =>
                        [
                            0 => 'IL',
                            1 => 'IN',
                            2 => 'KY',
                            3 => 'OH',
                            4 => 'CO',
                            5 => 'GA',
                            6 => 'MO',
                            7 => 'TN',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.2',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '150756',
                        ],
                ],
            70 =>
                [
                    'price' => '1.01',
                    'cap' => 6,
                    'states' =>
                        [
                            0 => 'CT',
                            1 => 'MA',
                            2 => 'NJ',
                            3 => 'NY',
                            4 => 'OH',
                            5 => 'PA',
                            6 => 'RI',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.7',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '191621',
                        ],
                ],
            71 =>
                [
                    'price' => '0.75',
                    'cap' => 3142,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '3.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '170286',
                        ],
                ],
            72 =>
                [
                    'price' => '0.35',
                    'cap' => 1204,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '2.8',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '191649',
                        ],
                ],
            73 =>
                [
                    'price' => '1.00',
                    'cap' => 1512,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'FEMALE ONLY',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '232308',
                        ],
                ],
            74 =>
                [
                    'price' => '0.70',
                    'cap' => 1126,
                    'states' =>
                        [
                            0 => 'IL',
                            1 => 'IN',
                            2 => 'MI',
                            3 => 'MN',
                            4 => 'OH',
                            5 => 'WI',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '170301',
                        ],
                ],
            75 =>
                [
                    'price' => '0.90',
                    'cap' => 638,
                    'states' =>
                        [
                            0 => 'AZ',
                            1 => 'CA',
                            2 => 'NV',
                            3 => 'OR',
                            4 => 'WA',
                            5 => 'CO',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '120537',
                        ],
                ],
            76 =>
                [
                    'price' => '0.70',
                    'cap' => 910,
                    'states' =>
                        [
                            0 => 'AZ',
                            1 => 'CA',
                            2 => 'CO',
                            3 => 'ID',
                            4 => 'MT',
                            5 => 'NV',
                            6 => 'NM',
                            7 => 'ND',
                            8 => 'OR',
                            9 => 'SD',
                            10 => 'UT',
                            11 => 'WA',
                            12 => 'WY',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '115755',
                        ],
                ],
            77 =>
                [
                    'price' => '0.40',
                    'cap' => 1115,
                    'states' =>
                        [
                            0 => 'AL',
                            1 => 'AK',
                            2 => 'AZ',
                            3 => 'AR',
                            4 => 'CA',
                            5 => 'CO',
                            6 => 'CT',
                            7 => 'DE',
                            8 => 'FL',
                            9 => 'GA',
                            10 => 'HI',
                            11 => 'ID',
                            12 => 'IN',
                            13 => 'IA',
                            14 => 'KS',
                            15 => 'KY',
                            16 => 'LA',
                            17 => 'ME',
                            18 => 'MD',
                            19 => 'MA',
                            20 => 'MI',
                            21 => 'MN',
                            22 => 'MS',
                            23 => 'MO',
                            24 => 'MT',
                            25 => 'NE',
                            26 => 'NV',
                            27 => 'NH',
                            28 => 'NJ',
                            29 => 'NM',
                            30 => 'NY',
                            31 => 'NC',
                            32 => 'ND',
                            33 => 'OH',
                            34 => 'OK',
                            35 => 'OR',
                            36 => 'PA',
                            37 => 'RI',
                            38 => 'SC',
                            39 => 'SD',
                            40 => 'TN',
                            41 => 'TX',
                            42 => 'UT',
                            43 => 'VT',
                            44 => 'VA',
                            45 => 'WA',
                            46 => 'WV',
                            47 => 'WI',
                            48 => 'WY',
                            49 => 'DC',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.3',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '145725',
                        ],
                ],
            78 =>
                [
                    'price' => '0.75',
                    'cap' => 350,
                    'states' =>
                        [
                            0 => 'IA',
                            1 => 'IL',
                            2 => 'IN',
                            3 => 'KY',
                            4 => 'MI',
                            5 => 'MN',
                            6 => 'MO',
                            7 => 'WI',
                            8 => 'TX',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '145813',
                        ],
                ],
            79 =>
                [
                    'price' => '2.00',
                    'cap' => 9,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'CT',
                            2 => 'DC',
                            3 => 'DE',
                            4 => 'FL',
                            5 => 'GA',
                            6 => 'MA',
                            7 => 'MD',
                            8 => 'ME',
                            9 => 'NC',
                            10 => 'NH',
                            11 => 'NJ',
                            12 => 'NY',
                            13 => 'OH',
                            14 => 'PA',
                            15 => 'RI',
                            16 => 'TX',
                            17 => 'VA',
                            18 => 'VT',
                            19 => 'PR',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '213011',
                        ],
                ],
            80 =>
                [
                    'price' => '0.96',
                    'cap' => 27,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '151290',
                        ],
                ],
            81 =>
                [
                    'price' => '2.00',
                    'cap' => 60,
                    'states' =>
                        [
                            0 => 'IL',
                            1 => 'IN',
                            2 => 'KY',
                            3 => 'OH',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '31241',
                        ],
                ],
            82 =>
                [
                    'price' => '0.66',
                    'cap' => 167,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.8',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '151111',
                        ],
                ],
            83 =>
                [
                    'price' => '0.75',
                    'cap' => 13220,
                    'states' =>
                        [
                            0 => 'IL',
                            1 => 'IN',
                            2 => 'MI',
                            3 => 'OH',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.6',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '151801',
                        ],
                ],
            84 =>
                [
                    'price' => '0.50',
                    'cap' => 911,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '153603',
                        ],
                ],
            85 =>
                [
                    'price' => '0.20',
                    'cap' => 827,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.3',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '232423',
                        ],
                ],
            86 =>
                [
                    'price' => '1.50',
                    'cap' => 217,
                    'states' =>
                        [
                            0 => 'CT',
                            1 => 'IL',
                            2 => 'IN',
                            3 => 'MI',
                            4 => 'NJ',
                            5 => 'NY',
                            6 => 'OH',
                            7 => 'PA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.7',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '203368',
                        ],
                ],
            87 =>
                [
                    'price' => '0.30',
                    'cap' => 1146,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2021,
                            1 => 2022,
                            2 => 2023,
                            3 => 2024,
                        ],
                    'gpa' => '3.8',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '162928',
                        ],
                ],
            88 =>
                [
                    'price' => '2.50',
                    'cap' => 123,
                    'states' =>
                        [
                            0 => 'AL',
                            1 => 'FL',
                            2 => 'GA',
                            3 => 'MS',
                            4 => 'TN',
                            5 => 'TX',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.0',
                    'gender' => 'FEMALE',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '101541',
                        ],
                ],
            89 =>
                [
                    'price' => '0.85',
                    'cap' => 317,
                    'states' =>
                        [
                            0 => 'IL',
                            1 => 'IN',
                            2 => 'MD',
                            3 => 'MI',
                            4 => 'NJ',
                            5 => 'NY',
                            6 => 'OH',
                            7 => 'PA',
                            8 => 'VA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '203517',
                        ],
                ],
            90 =>
                [
                    'price' => '0.60',
                    'cap' => 1134,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '203535',
                        ],
                ],
            91 =>
                [
                    'price' => '1.00',
                    'cap' => 96,
                    'states' =>
                        [
                            0 => 'PA',
                            1 => 'CT',
                            2 => 'DE',
                            3 => 'MD',
                            4 => 'NJ',
                            5 => 'NY',
                            6 => 'VA',
                            7 => 'MA',
                            8 => 'RI',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.7',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '213321',
                        ],
                ],
            92 =>
                [
                    'price' => '2.00',
                    'cap' => 457,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => '222,222,20,20,329,329,1654,1654,1653,1653,354,354,1650,1650,427,427,195,195,469,469,471,471,511,511,1647,1647,21,21,550,550,551,551,570,570,194,194,1644,1644,582,582,1648,1648,37,37,589,589,590,590,182,182,694,694,24,24,696,696,330,330,698,698,25,25,1645,1645,26,26,833,833,839,839,188,188,854,854,877,877,1149,1149,972,972,1003,1003,1004,1004,1005,1005,1006,1006,1649,1649,1008,1008,1010,1010,1011,1011,1646,1646,1013,1013,31,31,1652,1652,33,33,1163,1163,1190,1190,35,35,1419,1419,1345,1345,1352,1352,1354,1354,1422,1422,1423,1423,1425,1425,1651,1651,',
                    'iped' =>
                        [
                            0 => '117168',
                        ],
                ],
            93 =>
                [
                    'price' => '2.00',
                    'cap' => 330,
                    'states' =>
                        [
                            0 => 'IL',
                            1 => 'MI',
                            2 => 'NY',
                            3 => 'OH',
                            4 => 'PA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '203580',
                        ],
                ],
            94 =>
                [
                    'price' => '0.85',
                    'cap' => 436,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.75',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '170675',
                        ],
                ],
            95 =>
                [
                    'price' => '1.00',
                    'cap' => 673,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '239017',
                        ],
                ],
            96 =>
                [
                    'price' => '0.91',
                    'cap' => 113,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'CT',
                            2 => 'FL',
                            3 => 'GA',
                            4 => 'MA',
                            5 => 'MD',
                            6 => 'NC',
                            7 => 'NH',
                            8 => 'NY',
                            9 => 'PA',
                            10 => 'SC',
                            11 => 'TN',
                            12 => 'TX',
                            13 => 'VA',
                            14 => 'VT',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '198808',
                        ],
                ],
            97 =>
                [
                    'price' => '1.00',
                    'cap' => 2397,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '177968',
                        ],
                ],
            98 =>
                [
                    'price' => '0.30',
                    'cap' => 1648,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '159656',
                        ],
                ],
            99 =>
                [
                    'price' => '0.60',
                    'cap' => 492,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '132657',
                        ],
                ],
            100 =>
                [
                    'price' => '2.00',
                    'cap' => 139,
                    'states' =>
                        [
                            0 => 'NY',
                            1 => 'OH',
                            2 => 'PA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '203775',
                        ],
                ],
            101 =>
                [
                    'price' => '0.94',
                    'cap' => 717,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.8',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '151777',
                        ],
                ],
            102 =>
                [
                    'price' => '0.69',
                    'cap' => 4,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.2',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '192703',
                        ],
                ],
            103 =>
                [
                    'price' => '0.30',
                    'cap' => 514,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '192749',
                        ],
                ],
            104 =>
                [
                    'price' => '1.20',
                    'cap' => 57,
                    'states' =>
                        [
                            0 => 'CT',
                            1 => 'IL',
                            2 => 'IN',
                            3 => 'ME',
                            4 => 'MD',
                            5 => 'MA',
                            6 => 'NH',
                            7 => 'NJ',
                            8 => 'NY',
                            9 => 'OH',
                            10 => 'PA',
                            11 => 'RI',
                            12 => 'VT',
                            13 => 'VA',
                            14 => 'WV',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.7',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '203845',
                        ],
                ],
            105 =>
                [
                    'price' => '2.00',
                    'cap' => 117,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.75',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '163295',
                        ],
                ],
            106 =>
                [
                    'price' => '0.60',
                    'cap' => 379,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.7',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '192864',
                        ],
                ],
            107 =>
                [
                    'price' => '1.00',
                    'cap' => 469,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.7',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '166674',
                        ],
                ],
            108 =>
                [
                    'price' => '0.60',
                    'cap' => 11968,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.35',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '140447',
                        ],
                ],
            109 =>
                [
                    'price' => '0.10',
                    'cap' => 108,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '171100',
                        ],
                ],
            110 =>
                [
                    'price' => '2.00',
                    'cap' => 362,
                    'states' =>
                        [
                            0 => 'AR',
                            1 => 'KS',
                            2 => 'MO',
                            3 => 'OK',
                            4 => 'TX',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '245953',
                        ],
                ],
            111 =>
                [
                    'price' => '1.25',
                    'cap' => 102,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.8',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '174127',
                        ],
                ],
            112 =>
                [
                    'price' => '1.50',
                    'cap' => 15,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '200253',
                        ],
                ],
            113 =>
                [
                    'price' => '0.75',
                    'cap' => 880,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '180416',
                        ],
                ],
            114 =>
                [
                    'price' => '3.00',
                    'cap' => 38,
                    'states' =>
                        [
                            0 => 'ME',
                            1 => 'VT',
                            2 => 'NH',
                            3 => 'MA',
                            4 => 'RI',
                            5 => 'CT',
                            6 => 'NY',
                            7 => 'NJ',
                            8 => 'PA',
                            9 => 'MD',
                            10 => 'VA',
                            11 => 'NC',
                            12 => 'DE',
                            13 => 'TX',
                            14 => 'FL',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '166911',
                        ],
                ],
            115 =>
                [
                    'price' => '2.00',
                    'cap' => 2523,
                    'states' =>
                        [
                            0 => 'IA',
                            1 => 'IL',
                            2 => 'IN',
                            3 => 'KS',
                            4 => 'MI',
                            5 => 'MN',
                            6 => 'MO',
                            7 => 'ND',
                            8 => 'NE',
                            9 => 'OH',
                            10 => 'SD',
                            11 => 'WI',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'Female',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '239390',
                        ],
                ],
            116 =>
                [
                    'price' => '0.60',
                    'cap' => 4079,
                    'states' =>
                        [
                            0 => 'AZ',
                            1 => 'CA',
                            2 => 'CO',
                            3 => 'HI',
                            4 => 'MN',
                            5 => 'NV',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.7',
                    'gender' => 'Female',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '119173',
                        ],
                ],
            117 =>
                [
                    'price' => '0.70',
                    'cap' => 2916,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'CO',
                            2 => 'CT',
                            3 => 'DC',
                            4 => 'IL',
                            5 => 'MA',
                            6 => 'MD',
                            7 => 'ME',
                            8 => 'MN',
                            9 => 'NJ',
                            10 => 'NY',
                            11 => 'PA',
                            12 => 'VA',
                            13 => 'WA',
                            14 => 'FL',
                            15 => 'GA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.3',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '214175',
                        ],
                ],
            118 =>
                [
                    'price' => '2.00',
                    'cap' => 12,
                    'states' =>
                        [
                            0 => 'CO',
                            1 => 'KS',
                            2 => 'MI',
                            3 => 'MN',
                            4 => 'MO',
                            5 => 'NE',
                            6 => 'SD',
                            7 => 'IA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '181376',
                        ],
                ],
            119 =>
                [
                    'price' => '0.50',
                    'cap' => 1657,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '2.8',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '194091',
                        ],
                ],
            120 =>
                [
                    'price' => '1.00',
                    'cap' => 24,
                    'states' =>
                        [
                            0 => 'IL',
                            1 => 'IN',
                            2 => 'IA',
                            3 => 'MI',
                            4 => 'MO',
                            5 => 'WI',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.75',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '147660',
                        ],
                ],
            121 =>
                [
                    'price' => '0.80',
                    'cap' => 200,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '147679',
                        ],
                ],
            122 =>
                [
                    'price' => '0.59',
                    'cap' => 423,
                    'states' =>
                        [
                            0 => 'IA',
                            1 => 'IL',
                            2 => 'IN',
                            3 => 'MI',
                            4 => 'MN',
                            5 => 'MO',
                            6 => 'WI',
                            7 => 'OH',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.7',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '147703',
                        ],
                ],
            123 =>
                [
                    'price' => '0.75',
                    'cap' => 3961,
                    'states' =>
                        [
                            0 => 'CT',
                            1 => 'DE',
                            2 => 'MA',
                            3 => 'MD',
                            4 => 'NH',
                            5 => 'NJ',
                            6 => 'NY',
                            7 => 'RI',
                            8 => 'VT',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '230931',
                        ],
                ],
            124 =>
                [
                    'price' => '0.88',
                    'cap' => 101,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '239512',
                        ],
                ],
            125 =>
                [
                    'price' => '2.00',
                    'cap' => 195,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'CT',
                            2 => 'MA',
                            3 => 'ME',
                            4 => 'NH',
                            5 => 'NJ',
                            6 => 'NY',
                            7 => 'PA',
                            8 => 'TX',
                            9 => 'VT',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.3',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '230995',
                        ],
                ],
            126 =>
                [
                    'price' => '0.94',
                    'cap' => 1983,
                    'states' =>
                        [
                            0 => 'AZ',
                            1 => 'CA',
                            2 => 'CO',
                            3 => 'HI',
                            4 => 'NM',
                            5 => 'NV',
                            6 => 'OR',
                            7 => 'TX',
                            8 => 'UT',
                            9 => 'WA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '120184',
                        ],
                ],
            127 =>
                [
                    'price' => '2.00',
                    'cap' => 147,
                    'states' =>
                        [
                            0 => 'DC',
                            1 => 'MA',
                            2 => 'MD',
                            3 => 'NC',
                            4 => 'NJ',
                            5 => 'NY',
                            6 => 'PA',
                            7 => 'TX',
                            8 => 'VA',
                            9 => 'PR',
                            10 => 'CA',
                            11 => 'FL',
                            12 => 'GA',
                            13 => 'IL',
                            14 => 'OH',
                            15 => 'WV',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.75',
                    'gender' => 'Female only',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '163578',
                        ],
                ],
            128 =>
                [
                    'price' => '0.71',
                    'cap' => 293,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2021,
                            1 => 2022,
                            2 => 2023,
                        ],
                    'gpa' => '3.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '204501',
                        ],
                ],
            129 =>
                [
                    'price' => '0.80',
                    'cap' => 24,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.7',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '120254',
                        ],
                ],
            130 =>
                [
                    'price' => '1.00',
                    'cap' => 380,
                    'states' =>
                        [
                            0 => 'IL',
                            1 => 'IN',
                            2 => 'KY',
                            3 => 'MI',
                            4 => 'OH',
                            5 => 'TN',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '201964',
                        ],
                ],
            131 =>
                [
                    'price' => '0.80',
                    'cap' => 157,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '204909',
                        ],
                ],
            132 =>
                [
                    'price' => '0.90',
                    'cap' => 36,
                    'states' =>
                        [
                            0 => 'AR',
                            1 => 'KS',
                            2 => 'MO',
                            3 => 'OK',
                            4 => 'TX',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '206835',
                        ],
                ],
            133 =>
                [
                    'price' => '0.90',
                    'cap' => 1600,
                    'states' =>
                        [
                            0 => 'AL',
                            1 => 'AR',
                            2 => 'CA',
                            3 => 'CO',
                            4 => 'CT',
                            5 => 'DC',
                            6 => 'IL',
                            7 => 'IN',
                            8 => 'KS',
                            9 => 'KY',
                            10 => 'LA',
                            11 => 'MA',
                            12 => 'MD',
                            13 => 'MI',
                            14 => 'MN',
                            15 => 'MO',
                            16 => 'NC',
                            17 => 'NE',
                            18 => 'NJ',
                            19 => 'NM',
                            20 => 'NV',
                            21 => 'NY',
                            22 => 'OH',
                            23 => 'OK',
                            24 => 'PA',
                            25 => 'SC',
                            26 => 'TX',
                            27 => 'VA',
                            28 => 'WA',
                            29 => 'WI',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '207582',
                        ],
                ],
            134 =>
                [
                    'price' => '0.70',
                    'cap' => 1760,
                    'states' =>
                        [
                            0 => 'AZ',
                            1 => 'CA',
                            2 => 'CO',
                            3 => 'ID',
                            4 => 'MT',
                            5 => 'OR',
                            6 => 'WA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '440828',
                        ],
                ],
            135 =>
                [
                    'price' => '0.55',
                    'cap' => 2890,
                    'states' =>
                        [
                            0 => 'AK',
                            1 => 'AZ',
                            2 => 'CA',
                            3 => 'CO',
                            4 => 'HI',
                            5 => 'ID',
                            6 => 'MN',
                            7 => 'MT',
                            8 => 'NV',
                            9 => 'OR',
                            10 => 'WA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '236230',
                        ],
                ],
            136 =>
                [
                    'price' => '3.00',
                    'cap' => 55,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.2',
                    'gender' => 'N/A',
                    'degree' => '222,222,20,20,329,329,1654,1654,1653,1653,354,354,1650,1650,427,427,195,195,469,469,471,471,511,511,1647,1647,21,21,550,550,551,551,570,570,194,194,1644,1644,582,582,1648,1648,37,37,589,589,590,590,182,182,694,694,24,24,696,696,330,330,698,698,25,25,1645,1645,26,26,833,833,839,839,188,188,854,854,877,877,1149,1149,972,972,1003,1003,1004,1004,1005,1005,1006,1006,1649,1649,1008,1008,1010,1010,1011,1011,1646,1646,1013,1013,31,31,1652,1652,33,33,1163,1163,1190,1190,35,35,1419,1419,1345,1345,1352,1352,1354,1354,1422,1422,1423,1423,1425,1425,1651,1651,',
                    'iped' =>
                        [
                            0 => '209603',
                        ],
                ],
            137 =>
                [
                    'price' => '0.80',
                    'cap' => 1808,
                    'states' =>
                        [
                            0 => 'AR',
                            1 => 'IL',
                            2 => 'KS',
                            3 => 'MO',
                            4 => 'NE',
                            5 => 'OK',
                            6 => 'TX',
                            7 => 'IA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '178721',
                        ],
                ],
            138 =>
                [
                    'price' => '1.78',
                    'cap' => 741,
                    'states' =>
                        [
                            0 => 'PA',
                            1 => 'NJ',
                            2 => 'MD',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '215275',
                        ],
                ],
            139 =>
                [
                    'price' => '1.00',
                    'cap' => 1992,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '194578',
                        ],
                ],
            140 =>
                [
                    'price' => '1.00',
                    'cap' => 270,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '199412',
                        ],
                ],
            141 =>
                [
                    'price' => '2.00',
                    'cap' => 2064,
                    'states' =>
                        [
                            0 => 'AR',
                            1 => 'CO',
                            2 => 'IA',
                            3 => 'IL',
                            4 => 'IN',
                            5 => 'KS',
                            6 => 'KY',
                            7 => 'MN',
                            8 => 'MO',
                            9 => 'NM',
                            10 => 'OK',
                            11 => 'TX',
                            12 => 'WI',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '3.2',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '148131',
                        ],
                ],
            142 =>
                [
                    'price' => '0.70',
                    'cap' => 2941,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '130226',
                        ],
                ],
            143 =>
                [
                    'price' => '1.00',
                    'cap' => 10402,
                    'states' =>
                        [
                            0 => 'CT',
                            1 => 'DC',
                            2 => 'FL',
                            3 => 'MA',
                            4 => 'MD',
                            5 => 'NC',
                            6 => 'NJ',
                            7 => 'NY',
                            8 => 'OH',
                            9 => 'PA',
                            10 => 'SC',
                            11 => 'VA',
                            12 => 'WV',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.8',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '233277',
                        ],
                ],
            144 =>
                [
                    'price' => '0.75',
                    'cap' => 108,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.8',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '233295',
                        ],
                ],
            145 =>
                [
                    'price' => '0.50',
                    'cap' => 8211,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.3',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '209922',
                        ],
                ],
            146 =>
                [
                    'price' => '0.60',
                    'cap' => 958,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.4',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '194824',
                        ],
                ],
            147 =>
                [
                    'price' => '0.80',
                    'cap' => 471,
                    'states' =>
                        [
                            0 => 'CT',
                            1 => 'DC',
                            2 => 'DE',
                            3 => 'MA',
                            4 => 'MD',
                            5 => 'NC',
                            6 => 'NJ',
                            7 => 'NY',
                            8 => 'PA',
                            9 => 'VA',
                            10 => 'VT',
                            11 => 'GA',
                            12 => 'SC',
                            13 => 'NH',
                            14 => 'RI',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.7',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '233426',
                        ],
                ],
            148 =>
                [
                    'price' => '2.50',
                    'cap' => 1639,
                    'states' =>
                        [
                            0 => 'PA',
                            1 => 'CT',
                            2 => 'MA',
                            3 => 'MD',
                            4 => 'ME',
                            5 => 'NH',
                            6 => 'NJ',
                            7 => 'OH',
                            8 => 'RI',
                            9 => 'VA',
                            10 => 'VT',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.1',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '215743',
                        ],
                ],
            149 =>
                [
                    'price' => '0.73',
                    'cap' => 1563,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'CT',
                            2 => 'FL',
                            3 => 'GA',
                            4 => 'IL',
                            5 => 'MA',
                            6 => 'NJ',
                            7 => 'ND',
                            8 => 'OH',
                            9 => 'PA',
                            10 => 'RI',
                            11 => 'TX',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '195809',
                        ],
                ],
            150 =>
                [
                    'price' => '2.00',
                    'cap' => 7,
                    'states' =>
                        [
                            0 => 'NY',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '31227',
                        ],
                ],
            151 =>
                [
                    'price' => '0.40',
                    'cap' => 6164,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.7',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '137032',
                        ],
                ],
            152 =>
                [
                    'price' => '1.75',
                    'cap' => 204,
                    'states' =>
                        [
                            0 => 'AK',
                            1 => 'CA',
                            2 => 'HI',
                            3 => 'ID',
                            4 => 'NV',
                            5 => 'OR',
                            6 => 'WA',
                            7 => 'TX',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '236452',
                        ],
                ],
            153 =>
                [
                    'price' => '1.15',
                    'cap' => 76,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '152390',
                        ],
                ],
            154 =>
                [
                    'price' => '1.00',
                    'cap' => 422,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'Female',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '199607',
                        ],
                ],
            155 =>
                [
                    'price' => '0.60',
                    'cap' => 433,
                    'states' =>
                        [
                            0 => 'WA',
                            1 => 'OR',
                            2 => 'AZ',
                            3 => 'TX',
                            4 => 'IL',
                            5 => 'NY',
                            6 => 'NJ',
                            7 => 'PA',
                            8 => 'CO',
                            9 => 'MN',
                            10 => 'MA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '3.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '122409',
                        ],
                ],
            156 =>
                [
                    'price' => '0.93',
                    'cap' => 1081,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '140951',
                        ],
                ],
            157 =>
                [
                    'price' => '0.60',
                    'cap' => 2400,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '186584',
                        ],
                ],
            158 =>
                [
                    'price' => '1.02',
                    'cap' => 236,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.7',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '233541',
                        ],
                ],
            159 =>
                [
                    'price' => '0.75',
                    'cap' => 136,
                    'states' =>
                        [
                            0 => 'CT',
                            1 => 'DC',
                            2 => 'MA',
                            3 => 'NH',
                            4 => 'NJ',
                            5 => 'NY',
                            6 => 'RI',
                            7 => 'VA and VT',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '2.8',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '195474',
                        ],
                ],
            160 =>
                [
                    'price' => '0.90',
                    'cap' => 5191,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'NV',
                            2 => 'OR',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.8',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '123457',
                        ],
                ],
            161 =>
                [
                    'price' => '0.70',
                    'cap' => 1631,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.7',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '195526',
                        ],
                ],
            162 =>
                [
                    'price' => '1.00',
                    'cap' => 62,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '399911',
                        ],
                ],
            163 =>
                [
                    'price' => '2.00',
                    'cap' => 287,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => '1451 320 1452 321 19 16 17 662 853 18 1453',
                    'iped' =>
                        [
                            0 => '123952',
                        ],
                ],
            164 =>
                [
                    'price' => '0.95',
                    'cap' => 103,
                    'states' =>
                        [
                            0 => 'CT',
                            1 => 'ME',
                            2 => 'MA',
                            3 => 'NH',
                            4 => 'NJ',
                            5 => 'NY',
                            6 => 'RI',
                            7 => 'VT',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.7',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '130493',
                        ],
                ],
            165 =>
                [
                    'price' => '0.30',
                    'cap' => 1193,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '183026',
                        ],
                ],
            166 =>
                [
                    'price' => '1.00',
                    'cap' => 1288,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'OR',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '2.75',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '210146',
                        ],
                ],
            167 =>
                [
                    'price' => '1.14',
                    'cap' => 200,
                    'states' =>
                        [
                            0 => 'AL',
                            1 => 'DE',
                            2 => 'FL',
                            3 => 'GA',
                            4 => 'IL',
                            5 => 'IN',
                            6 => 'MD',
                            7 => 'NC',
                            8 => 'SC',
                            9 => 'TN',
                            10 => 'VA',
                            11 => 'WV',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.8',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '217776',
                        ],
                ],
            168 =>
                [
                    'price' => '0.90',
                    'cap' => 1509,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.7',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '175078',
                        ],
                ],
            169 =>
                [
                    'price' => '0.75',
                    'cap' => 151,
                    'states' =>
                        [
                            0 => 'LA',
                            1 => 'NM',
                            2 => 'OK',
                            3 => 'TX',
                            4 => 'AR',
                            5 => 'IN',
                            6 => 'AL',
                            7 => 'IL',
                            8 => 'WA',
                            9 => 'NE',
                            10 => 'KS',
                            11 => 'AZ',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '228325',
                        ],
                ],
            170 =>
                [
                    'price' => '1.11',
                    'cap' => 368,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.4',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '227845',
                        ],
                ],
            171 =>
                [
                    'price' => '3.00',
                    'cap' => 665,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '163976',
                        ],
                ],
            172 =>
                [
                    'price' => '1.00',
                    'cap' => 1020,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '245652',
                        ],
                ],
            173 =>
                [
                    'price' => '0.75',
                    'cap' => 2832,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '195216',
                        ],
                ],
            174 =>
                [
                    'price' => '0.50',
                    'cap' => 218,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '163912',
                        ],
                ],
            175 =>
                [
                    'price' => '0.95',
                    'cap' => 457,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.7',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '195243',
                        ],
                ],
            176 =>
                [
                    'price' => '0.40',
                    'cap' => 2150,
                    'states' =>
                        [
                            0 => 'AR',
                            1 => 'LA',
                            2 => 'TX',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '228431',
                        ],
                ],
            177 =>
                [
                    'price' => '1.50',
                    'cap' => 229,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                        ],
                    'gpa' => '2.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '231095',
                        ],
                ],
            178 =>
                [
                    'price' => '0.50',
                    'cap' => 1472,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '164173',
                        ],
                ],
            179 =>
                [
                    'price' => '2.50',
                    'cap' => 102,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '31221',
                        ],
                ],
            180 =>
                [
                    'price' => '0.49',
                    'cap' => 4672,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.7',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '168005',
                        ],
                ],
            181 =>
                [
                    'price' => '0.54',
                    'cap' => 191,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'CT',
                            2 => 'DC',
                            3 => 'DE',
                            4 => 'FL',
                            5 => 'MA',
                            6 => 'MD',
                            7 => 'ME',
                            8 => 'NH',
                            9 => 'NJ',
                            10 => 'NY',
                            11 => 'OH',
                            12 => 'PA',
                            13 => 'RI',
                            14 => 'VA',
                            15 => 'VT',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '196006',
                        ],
                ],
            182 =>
                [
                    'price' => '1.00',
                    'cap' => 405,
                    'states' =>
                        [
                            0 => 'NY',
                            1 => 'CT',
                            2 => 'RI',
                            3 => 'MA',
                            4 => 'NH',
                            5 => 'VT',
                            6 => 'IL',
                            7 => 'NJ',
                            8 => 'PA',
                            9 => 'MD',
                            10 => 'DC',
                            11 => 'VA',
                            12 => 'NC',
                            13 => 'SC',
                            14 => 'GA',
                            15 => 'FL',
                            16 => 'CA',
                            17 => 'OH',
                            18 => 'TX',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.8',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '196149',
                        ],
                ],
            183 =>
                [
                    'price' => '1.80',
                    'cap' => 191,
                    'states' =>
                        [
                            0 => 'CT',
                            1 => 'MA',
                            2 => 'NJ',
                            3 => 'NY',
                            4 => 'PA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.8',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '196237',
                        ],
                ],
            184 =>
                [
                    'price' => '0.78',
                    'cap' => 478,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '196194',
                        ],
                ],
            185 =>
                [
                    'price' => '1.00',
                    'cap' => 8631,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '196200',
                        ],
                ],
            186 =>
                [
                    'price' => '1.00',
                    'cap' => 3523,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.2',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '196185',
                        ],
                ],
            187 =>
                [
                    'price' => '3.00',
                    'cap' => 216,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'CT',
                            2 => 'DE',
                            3 => 'FL',
                            4 => 'MA',
                            5 => 'ME',
                            6 => 'NC',
                            7 => 'NH',
                            8 => 'NJ',
                            9 => 'NY',
                            10 => 'PA',
                            11 => 'RI',
                            12 => 'TX',
                            13 => 'VT',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '2.2',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '196015',
                        ],
                ],
            188 =>
                [
                    'price' => '0.96',
                    'cap' => 3498,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '216278',
                        ],
                ],
            189 =>
                [
                    'price' => '2.00',
                    'cap' => 302,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'DC',
                            2 => 'GA',
                            3 => 'MD',
                            4 => 'NC',
                            5 => 'NJ',
                            6 => 'NY',
                            7 => 'PA',
                            8 => 'TN',
                            9 => 'TX',
                            10 => 'VA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '233718',
                        ],
                ],
            190 =>
                [
                    'price' => '0.75',
                    'cap' => 5721,
                    'states' =>
                        [
                            0 => 'TX',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.75',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '228529',
                        ],
                ],
            191 =>
                [
                    'price' => '0.75',
                    'cap' => 358,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'CT',
                            2 => 'DC',
                            3 => 'DE',
                            4 => 'FL',
                            5 => 'MA',
                            6 => 'MD',
                            7 => 'ME',
                            8 => 'NH',
                            9 => 'NJ',
                            10 => 'NY',
                            11 => 'OH',
                            12 => 'PA',
                            13 => 'RI',
                            14 => 'TX',
                            15 => 'VA',
                            16 => 'VT',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.7',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '195234',
                        ],
                ],
            192 =>
                [
                    'price' => '1.00',
                    'cap' => 263,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'CO',
                            2 => 'ID',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.75',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '117751',
                        ],
                ],
            193 =>
                [
                    'price' => '0.90',
                    'cap' => 20548,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '31085',
                            1 => ' 31084',
                            2 => ' 193654',
                            3 => ' 31093',
                            4 => ' 31141',
                            5 => ' 31144',
                            6 => ' 31092',
                        ],
                ],
            194 =>
                [
                    'price' => '1.50',
                    'cap' => 1428,
                    'states' =>
                        [
                            0 => 'CT',
                            1 => 'DC',
                            2 => 'DE',
                            3 => 'MA',
                            4 => 'MD',
                            5 => 'NH',
                            6 => 'NJ',
                            7 => 'NY',
                            8 => 'OH',
                            9 => 'PA',
                            10 => 'RI',
                            11 => 'VA',
                            12 => 'VT',
                            13 => 'WV',
                            14 => 'ME',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.25',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '216357',
                        ],
                ],
            195 =>
                [
                    'price' => '0.75',
                    'cap' => 10577,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.75',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '215099',
                        ],
                ],
            196 =>
                [
                    'price' => '1.00',
                    'cap' => 40,
                    'states' =>
                        [
                            0 => 'AL',
                            1 => 'FL',
                            2 => 'GA',
                            3 => 'SC',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '137953',
                        ],
                ],
            197 =>
                [
                    'price' => '1.00',
                    'cap' => 62,
                    'states' =>
                        [
                            0 => 'IA',
                            1 => 'MN',
                            2 => 'MT',
                            3 => 'ND',
                            4 => 'NE',
                            5 => 'SD',
                            6 => 'WI',
                            7 => 'WY',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '200484',
                        ],
                ],
            198 =>
                [
                    'price' => '1.00',
                    'cap' => 1376,
                    'states' =>
                        [
                            0 => 'IL',
                            1 => 'IN',
                            2 => 'MI',
                            3 => 'MN',
                            4 => 'WI',
                            5 => 'CA',
                            6 => 'IA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.75',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '149505',
                        ],
                ],
            199 =>
                [
                    'price' => '1.00',
                    'cap' => 8242,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.6',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '197036',
                        ],
                ],
            200 =>
                [
                    'price' => '0.75',
                    'cap' => 189,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.7',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '130624',
                        ],
                ],
            201 =>
                [
                    'price' => '0.90',
                    'cap' => 130,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '102614',
                        ],
                ],
            202 =>
                [
                    'price' => '0.40',
                    'cap' => 254,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.75',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '104179',
                        ],
                ],
            203 =>
                [
                    'price' => '0.80',
                    'cap' => 301,
                    'states' =>
                        [
                            0 => 'CT',
                            1 => 'DC',
                            2 => 'MA',
                            3 => 'MD',
                            4 => 'NJ',
                            5 => 'NY',
                            6 => 'PA',
                            7 => 'RI',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '2.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '128744',
                        ],
                ],
            204 =>
                [
                    'price' => '1.50',
                    'cap' => 346,
                    'states' =>
                        [
                            0 => 'NJ',
                            1 => 'MA',
                            2 => 'PA',
                            3 => 'OH',
                            4 => 'IL',
                            5 => 'CA',
                            6 => 'FL',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '196088',
                        ],
                ],
            205 =>
                [
                    'price' => '0.10',
                    'cap' => 661,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2021,
                            1 => 2022,
                            2 => 2023,
                        ],
                    'gpa' => '3.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '110705',
                        ],
                ],
            206 =>
                [
                    'price' => '0.24',
                    'cap' => 1507,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2021,
                            1 => 2022,
                            2 => 2023,
                        ],
                    'gpa' => '3.4',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '110680',
                        ],
                ],
            207 =>
                [
                    'price' => '0.55',
                    'cap' => 25309,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2021,
                            1 => 2022,
                            2 => 2023,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '445188',
                        ],
                ],
            208 =>
                [
                    'price' => '0.15',
                    'cap' => 1618,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.8',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '144050',
                        ],
                ],
            209 =>
                [
                    'price' => '1.20',
                    'cap' => 87,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'CT',
                            2 => 'DC',
                            3 => 'GA',
                            4 => 'IL',
                            5 => 'MA',
                            6 => 'MD',
                            7 => 'MI',
                            8 => 'MN',
                            9 => 'MO',
                            10 => 'NJ',
                            11 => 'NY',
                            12 => 'PA',
                            13 => 'RI',
                            14 => 'TX',
                            15 => 'VA',
                            16 => 'WI',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '3.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '201885',
                        ],
                ],
            210 =>
                [
                    'price' => '0.40',
                    'cap' => 477,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '127060',
                        ],
                ],
            211 =>
                [
                    'price' => '0.60',
                    'cap' => 1730,
                    'states' =>
                        [
                            0 => 'IL',
                            1 => 'KY',
                            2 => 'MI',
                            3 => 'MO',
                            4 => 'OH',
                            5 => 'TN',
                            6 => 'WI',
                            7 => 'IN',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '150534',
                        ],
                ],
            212 =>
                [
                    'price' => '0.30',
                    'cap' => 5899,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2021,
                            1 => 2022,
                            2 => 2023,
                        ],
                    'gpa' => '3.4',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '134130',
                        ],
                ],
            213 =>
                [
                    'price' => '0.35',
                    'cap' => 383,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2021,
                            1 => 2022,
                            2 => 2023,
                        ],
                    'gpa' => '3.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '139959',
                        ],
                ],
            214 =>
                [
                    'price' => '0.88',
                    'cap' => 47,
                    'states' =>
                        [
                            0 => 'AZ',
                            1 => 'CA',
                            2 => 'CO',
                            3 => 'HI',
                            4 => 'MT',
                            5 => 'NV',
                            6 => 'NM',
                            7 => 'ND',
                            8 => 'OR',
                            9 => 'SD',
                            10 => 'UT',
                            11 => 'WA',
                            12 => 'WY',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '142285',
                        ],
                ],
            215 =>
                [
                    'price' => '0.50',
                    'cap' => 440,
                    'states' =>
                        [
                            0 => 'Nationwide except IL',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '3.25',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '145600',
                        ],
                ],
            216 =>
                [
                    'price' => '2.11',
                    'cap' => 34,
                    'states' =>
                        [
                            0 => 'IA',
                            1 => 'IL',
                            2 => 'IN',
                            3 => 'MO',
                            4 => 'WI',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.75',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '148654',
                        ],
                ],
            217 =>
                [
                    'price' => '0.54',
                    'cap' => 159,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '153658',
                        ],
                ],
            218 =>
                [
                    'price' => '1.00',
                    'cap' => 530,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '200217',
                        ],
                ],
            219 =>
                [
                    'price' => '1.00',
                    'cap' => 25,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'CT',
                            2 => 'DC',
                            3 => 'FL',
                            4 => 'MA',
                            5 => 'MD',
                            6 => 'ME',
                            7 => 'NH',
                            8 => 'NJ',
                            9 => 'NY',
                            10 => 'PA',
                            11 => 'RI',
                            12 => 'VA',
                            13 => 'VT',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.3',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '166513',
                        ],
                ],
            220 =>
                [
                    'price' => '1.53',
                    'cap' => 53,
                    'states' =>
                        [
                            0 => 'IL',
                            1 => 'IN',
                            2 => 'MI',
                            3 => 'OH',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '171137',
                        ],
                ],
            221 =>
                [
                    'price' => '0.94',
                    'cap' => 68,
                    'states' =>
                        [
                            0 => 'IL',
                            1 => 'IN',
                            2 => 'MI',
                            3 => 'OH',
                            4 => 'WI',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '171146',
                        ],
                ],
            222 =>
                [
                    'price' => '0.40',
                    'cap' => 504,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '3.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '174066',
                        ],
                ],
            223 =>
                [
                    'price' => '0.68',
                    'cap' => 37,
                    'states' =>
                        [
                            0 => 'NC',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '199069',
                        ],
                ],
            224 =>
                [
                    'price' => '0.20',
                    'cap' => 1997,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'CO',
                            2 => 'IL',
                            3 => 'IA',
                            4 => 'KS',
                            5 => 'MI',
                            6 => 'MN',
                            7 => 'MO',
                            8 => 'NE',
                            9 => 'OK',
                            10 => 'SD',
                            11 => 'TX',
                            12 => 'WI',
                            13 => 'WY',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '181464',
                        ],
                ],
            225 =>
                [
                    'price' => '0.40',
                    'cap' => 329,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '182290',
                        ],
                ],
            226 =>
                [
                    'price' => '0.60',
                    'cap' => 841,
                    'states' =>
                        [
                            0 => 'NATionwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '161457',
                        ],
                ],
            227 =>
                [
                    'price' => '0.75',
                    'cap' => 822,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'CT',
                            2 => 'DC',
                            3 => 'DE',
                            4 => 'FL',
                            5 => 'MA',
                            6 => 'MD',
                            7 => 'ME',
                            8 => 'NH',
                            9 => 'NJ',
                            10 => 'NY',
                            11 => 'PA',
                            12 => 'RI',
                            13 => 'PR',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '129941',
                        ],
                ],
            228 =>
                [
                    'price' => '0.80',
                    'cap' => 21,
                    'states' =>
                        [
                            0 => 'AL',
                            1 => 'AR',
                            2 => 'CA',
                            3 => 'CO',
                            4 => 'FL',
                            5 => 'GA',
                            6 => 'IL',
                            7 => 'LA',
                            8 => 'MS',
                            9 => 'NJ',
                            10 => 'TN',
                            11 => 'TX',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '159939',
                        ],
                ],
            229 =>
                [
                    'price' => '0.50',
                    'cap' => 145,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '199148',
                        ],
                ],
            230 =>
                [
                    'price' => '0.75',
                    'cap' => 309,
                    'states' =>
                        [
                            0 => 'FL',
                            1 => 'GA',
                            2 => 'MD',
                            3 => 'NC',
                            4 => 'SC',
                            5 => 'VA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => '222,222,20,20,329,329,1654,1654,1653,1653,354,354,1650,1650,427,427,195,195,469,469,471,471,511,511,1647,1647,21,21,550,550,551,551,570,570,194,194,1644,1644,582,582,1648,1648,37,37,589,589,590,590,182,182,694,694,24,24,696,696,330,330,698,698,25,25,1645,1645,26,26,833,833,839,839,188,188,854,854,877,877,1149,1149,972,972,1003,1003,1004,1004,1005,1005,1006,1006,1649,1649,1008,1008,1010,1010,1011,1011,1646,1646,1013,1013,31,31,1652,1652,33,33,1163,1163,1190,1190,35,35,1419,1419,1345,1345,1352,1352,1354,1354,1422,1422,1423,1423,1425,1425,1651,1651,',
                    'iped' =>
                        [
                            0 => '199184',
                        ],
                ],
            231 =>
                [
                    'price' => '0.40',
                    'cap' => 46194,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.75',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '227216',
                        ],
                ],
            232 =>
                [
                    'price' => '1.00',
                    'cap' => 358,
                    'states' =>
                        [
                            0 => 'TX',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '484905',
                        ],
                ],
            233 =>
                [
                    'price' => '0.40',
                    'cap' => 1948,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '209551',
                        ],
                ],
            234 =>
                [
                    'price' => '0.80',
                    'cap' => 808,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '215284',
                        ],
                ],
            235 =>
                [
                    'price' => '0.33',
                    'cap' => 3145,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.75',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '215293',
                        ],
                ],
            236 =>
                [
                    'price' => '2.00',
                    'cap' => 876,
                    'states' =>
                        [
                            0 => 'FL',
                            1 => 'TX',
                            2 => 'AZ',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '215266',
                        ],
                ],
            237 =>
                [
                    'price' => '1.00',
                    'cap' => 148,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'MT',
                            2 => 'WA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2019,
                            1 => 2020,
                            2 => 2021,
                        ],
                    'gpa' => '2.8',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '180258',
                        ],
                ],
            238 =>
                [
                    'price' => '0.75',
                    'cap' => 269,
                    'states' =>
                        [
                            0 => 'CT',
                            1 => 'DC',
                            2 => 'DE',
                            3 => 'IL',
                            4 => 'MA',
                            5 => 'MD',
                            6 => 'ME',
                            7 => 'NJ',
                            8 => 'NY',
                            9 => 'PA',
                            10 => 'TX',
                            11 => 'VA',
                            12 => 'VT',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.9',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '217484',
                        ],
                ],
            239 =>
                [
                    'price' => '0.45',
                    'cap' => 18649,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.3',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '195030',
                        ],
                ],
            240 =>
                [
                    'price' => '0.40',
                    'cap' => 656,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '122612',
                        ],
                ],
            241 =>
                [
                    'price' => '0.60',
                    'cap' => 278,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '218742',
                        ],
                ],
            242 =>
                [
                    'price' => '0.50',
                    'cap' => 246,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.3',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '137351',
                        ],
                ],
            243 =>
                [
                    'price' => '0.80',
                    'cap' => 57,
                    'states' =>
                        [
                            0 => 'CT',
                            1 => 'MA',
                            2 => 'ME',
                            3 => 'NH',
                            4 => 'NY',
                            5 => 'RI',
                            6 => 'VT',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '161554',
                        ],
                ],
            244 =>
                [
                    'price' => '1.00',
                    'cap' => 46,
                    'states' =>
                        [
                            0 => 'DC',
                            1 => 'TX',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.8',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '227863',
                        ],
                ],
            245 =>
                [
                    'price' => '0.60',
                    'cap' => 1299,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '137847',
                        ],
                ],
            246 =>
                [
                    'price' => '0.70',
                    'cap' => 45,
                    'states' =>
                        [
                            0 => 'AR',
                            1 => 'LA',
                            2 => 'NM',
                            3 => 'OK',
                            4 => 'TX',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.75',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '228802',
                        ],
                ],
            247 =>
                [
                    'price' => '1.04',
                    'cap' => 1173,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '107558',
                        ],
                ],
            248 =>
                [
                    'price' => '0.61',
                    'cap' => 890,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '215132',
                        ],
                ],
            249 =>
                [
                    'price' => '0.30',
                    'cap' => 330,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.4',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '231174',
                        ],
                ],
            250 =>
                [
                    'price' => '1.00',
                    'cap' => 762,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'ID',
                            2 => 'OR',
                            3 => 'WA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '377555',
                        ],
                ],
            251 =>
                [
                    'price' => '0.81',
                    'cap' => 907,
                    'states' =>
                        [
                            0 => 'IA',
                            1 => 'IL',
                            2 => 'MI',
                            3 => 'MN',
                            4 => 'WI',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '240417',
                        ],
                ],
            252 =>
                [
                    'price' => '1.25',
                    'cap' => 249,
                    'states' =>
                        [
                            0 => 'MN',
                            1 => 'WI',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '240471',
                        ],
                ],
            253 =>
                [
                    'price' => '0.62',
                    'cap' => 2562,
                    'states' =>
                        [
                            0 => 'IA',
                            1 => 'IL',
                            2 => 'IN',
                            3 => 'KS',
                            4 => 'MN',
                            5 => 'MO',
                            6 => 'ND',
                            7 => 'NE',
                            8 => 'OK',
                            9 => 'SD',
                            10 => 'WI',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '154493',
                        ],
                ],
            254 =>
                [
                    'price' => '2.00',
                    'cap' => 496,
                    'states' =>
                        [
                            0 => 'OH',
                            1 => 'PA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '206349',
                        ],
                ],
            255 =>
                [
                    'price' => '0.31',
                    'cap' => 3634,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '448840',
                        ],
                ],
            256 =>
                [
                    'price' => '1.00',
                    'cap' => 323,
                    'states' =>
                        [
                            0 => 'IL',
                            1 => 'IN',
                            2 => 'MI',
                            3 => 'OH',
                            4 => 'TX',
                            5 => 'WI',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '152600',
                        ],
                ],
            257 =>
                [
                    'price' => '1.00',
                    'cap' => 284,
                    'states' =>
                        [
                            0 => 'AZ',
                            1 => 'CA',
                            2 => 'CO',
                            3 => 'HI',
                            4 => 'NV',
                            5 => 'OR',
                            6 => 'WA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.7',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '123651',
                        ],
                ],
            258 =>
                [
                    'price' => '0.40',
                    'cap' => 457,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '234030',
                        ],
                ],
            259 =>
                [
                    'price' => '1.01',
                    'cap' => 74,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2021,
                            1 => 2022,
                            2 => 2023,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '234085',
                        ],
                ],
            260 =>
                [
                    'price' => '1.00',
                    'cap' => 918,
                    'states' =>
                        [
                            0 => 'DE',
                            1 => 'MD',
                            2 => 'NJ',
                            3 => 'NY',
                            4 => 'PA',
                            5 => 'VA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '234173',
                        ],
                ],
            261 =>
                [
                    'price' => '3.00',
                    'cap' => 870,
                    'states' =>
                        [
                            0 => 'IL',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '152673',
                        ],
                ],
            262 =>
                [
                    'price' => '0.90',
                    'cap' => 836,
                    'states' =>
                        [
                            0 => 'IA',
                            1 => 'IL',
                            2 => 'IN',
                            3 => 'MN',
                            4 => 'WI',
                            5 => 'CO',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '154527',
                        ],
                ],
            263 =>
                [
                    'price' => '1.75',
                    'cap' => 456,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'FL',
                            2 => 'IL',
                            3 => 'KY',
                            4 => 'MO',
                            5 => 'NJ',
                            6 => 'NY',
                            7 => 'OH',
                            8 => 'TX',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.75',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '234207',
                        ],
                ],
            264 =>
                [
                    'price' => '0.30',
                    'cap' => 905,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.8',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '236939',
                        ],
                ],
            265 =>
                [
                    'price' => '0.85',
                    'cap' => 177,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '230782',
                        ],
                ],
            266 =>
                [
                    'price' => '1.20',
                    'cap' => 352,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'CT',
                            2 => 'DC',
                            3 => 'FL',
                            4 => 'IL',
                            5 => 'MA',
                            6 => 'MD',
                            7 => 'ME',
                            8 => 'MN',
                            9 => 'NH',
                            10 => 'NJ',
                            11 => 'NY',
                            12 => 'PA',
                            13 => 'TX',
                            14 => 'VT',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.2',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '197230',
                        ],
                ],
            267 =>
                [
                    'price' => '0.92',
                    'cap' => 1861,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '168227',
                        ],
                ],
            268 =>
                [
                    'price' => '0.50',
                    'cap' => 2582,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '130697',
                        ],
                ],
            269 =>
                [
                    'price' => '0.71',
                    'cap' => 758,
                    'states' =>
                        [
                            0 => 'CT',
                            1 => 'DC',
                            2 => 'DE',
                            3 => 'MD',
                            4 => 'NJ',
                            5 => 'NY',
                            6 => 'PA',
                            7 => 'VA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '216764',
                        ],
                ],
            270 =>
                [
                    'price' => '0.40',
                    'cap' => 1450,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                            3 => 2023,
                        ],
                    'gpa' => '2.75',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '238032',
                        ],
                ],
            271 =>
                [
                    'price' => '1.30',
                    'cap' => 337,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '237950',
                        ],
                ],
            272 =>
                [
                    'price' => '0.69',
                    'cap' => 117,
                    'states' =>
                        [
                            0 => 'FL',
                            1 => 'GA',
                            2 => 'MD',
                            3 => 'NC',
                            4 => 'NJ',
                            5 => 'SC',
                            6 => 'TN',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.7',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '200004',
                        ],
                ],
            273 =>
                [
                    'price' => '0.95',
                    'cap' => 2414,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '128391',
                        ],
                ],
            274 =>
                [
                    'price' => '0.60',
                    'cap' => 293,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '130776',
                        ],
                ],
            275 =>
                [
                    'price' => '1.21',
                    'cap' => 2534,
                    'states' =>
                        [
                            0 => 'MD',
                            1 => 'NJ',
                            2 => 'NY',
                            3 => 'OH',
                            4 => 'PA',
                            5 => 'VA',
                            6 => 'WV',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.8',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '216807',
                        ],
                ],
            276 =>
                [
                    'price' => '0.50',
                    'cap' => 466,
                    'states' =>
                        [
                            0 => 'AZ',
                            1 => 'CA',
                            2 => 'CO',
                            3 => 'ID',
                            4 => 'IL',
                            5 => 'MA',
                            6 => 'ME',
                            7 => 'MN',
                            8 => 'MT',
                            9 => 'NH',
                            10 => 'NM',
                            11 => 'NV',
                            12 => 'OR',
                            13 => 'TX',
                            14 => 'UT',
                            15 => 'VT',
                            16 => 'WA',
                            17 => 'WY',
                            18 => 'IA',
                            19 => 'IN',
                            20 => 'KS',
                            21 => 'MI',
                            22 => 'OH',
                            23 => 'PA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '230807',
                        ],
                ],
            277 =>
                [
                    'price' => '1.00',
                    'cap' => 147,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.4',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '149781',
                        ],
                ],
            278 =>
                [
                    'price' => '0.85',
                    'cap' => 108,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.2',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '168281',
                        ],
                ],
            279 =>
                [
                    'price' => '0.80',
                    'cap' => 410,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.0',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '237057',
                        ],
                ],
            280 =>
                [
                    'price' => '0.80',
                    'cap' => 335,
                    'states' =>
                        [
                            0 => 'FL',
                            1 => 'GA',
                            2 => 'MD',
                            3 => 'NC',
                            4 => 'SC',
                            5 => 'TN',
                            6 => 'VA',
                            7 => 'DC',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '199272',
                        ],
                ],
            281 =>
                [
                    'price' => '1.50',
                    'cap' => 234,
                    'states' =>
                        [
                            0 => 'MO',
                            1 => 'IL',
                            2 => 'IA',
                            3 => 'MN',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '154590',
                        ],
                ],
            282 =>
                [
                    'price' => '1.00',
                    'cap' => 4046,
                    'states' =>
                        [
                            0 => 'CA',
                            1 => 'NV',
                            2 => 'OR',
                            3 => 'AZ',
                            4 => 'WA',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '2.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '125897',
                        ],
                ],
            283 =>
                [
                    'price' => '0.60',
                    'cap' => 223,
                    'states' =>
                        [
                            0 => 'Nationwide',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                            2 => 2022,
                        ],
                    'gpa' => '3.5',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '168421',
                        ],
                ],
            284 =>
                [
                    'price' => '0.80',
                    'cap' => 2458,
                    'states' =>
                        [
                            0 => 'FL',
                            1 => 'NC',
                            2 => 'PA',
                            3 => 'VA',
                            4 => 'MD',
                        ],
                    'grad_year' =>
                        [
                            0 => 2020,
                            1 => 2021,
                        ],
                    'gpa' => '2.25',
                    'gender' => 'N/A',
                    'degree' => 'N/A',
                    'iped' =>
                        [
                            0 => '217059',
                        ],
                ],
        ];
    }


}

