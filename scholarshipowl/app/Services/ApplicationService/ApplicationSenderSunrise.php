<?php

namespace App\Services\ApplicationService;

use App\Entity\Account;
use App\Entity\Application;
use App\Entity\ApplicationFile;
use App\Entity\ApplicationImage;
use App\Entity\ApplicationInput;
use App\Entity\ApplicationSpecialEligibility;
use App\Entity\ApplicationStatus;
use App\Entity\ApplicationSurvey;
use App\Entity\ApplicationText;
use App\Entity\Contracts\RequirementContract;
use App\Entity\Eligibility;
use App\Entity\RequirementFile;
use App\Entity\RequirementImage;
use App\Entity\RequirementInput;
use App\Entity\RequirementSpecialEligibility;
use App\Entity\RequirementSurvey;
use App\Entity\RequirementText;
use App\Entity\Scholarship;
use App\Traits\SunriseSync;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Cache;

class ApplicationSenderSunrise extends ApplicationSenderAbstract
{

    use SunriseSync;

    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * @return Client
     */
    public function getHttpClient(array $config = [])
    {
        if ($this->httpClient === null) {
            $this->httpClient = new Client($config);
        }

        return $this->httpClient;
    }

    /**
     * @param Client $httpClient
     *
     * @return $this
     */
    public function setHttpClient(Client $httpClient)
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * @param Scholarship $scholarship
     * @param Account     $account
     *
     * @return array
     */
    public function prepareSubmitData(Scholarship $scholarship, Account $account, array $overrides = []) : array
    {
        $params = [];
        $this->populateRequirements($params, $scholarship, $account);

        $sendAsMultipartFormData = !empty($params);

        $repo = \EntityManager::getRepository(Eligibility::class);
        $eligibilities = $repo->createQueryBuilder('e')
            ->where('e.scholarship = :scholarship')
            ->setParameter('scholarship', $scholarship)
            ->getQuery()
            ->getResult();

        // SOWL's field ids
        $fieldIds = array_map(function(Eligibility $v) {
            return $v->getField()->getId();
        }, $eligibilities);

        // SOWL fieldId to Sunrise FieldId (text)
        $fieldsMap = $this->reverseEligibilityFieldsMap();

        foreach ($fieldIds as $fId) {
            if (isset($fieldsMap[$fId])) {
                if ($sendAsMultipartFormData) {
                    $params[] = [
                        'name' => "data[attributes][{$fieldsMap[$fId]}]",
                        'contents' => $overrides[$fieldsMap[$fId]] ?? $this->resolveEligibilityField($account, $fId)
                    ];
                } else {
                    $params['data']['attributes'][$fieldsMap[$fId]] = $overrides[$fieldsMap[$fId]] ??
                         $this->resolveEligibilityField($account, $fId);
                }
            }
        }

        if ($sendAsMultipartFormData) {
            $params[] = [
                'name' => 'data[attributes][source]',
                'contents' => 'sowl'
            ];
        } else {
            $params['data']['attributes']['source'] = 'sowl';
        }

        return $params;
    }

    /**
     * @param Scholarship $scholarship
     * @param array       $submitData
     *
     * @return mixed
     */
    public function sendApplication(Scholarship $scholarship, array $submitData, Application $application)
    {
        if ($scholarship->getApplicationType() !== Scholarship::APPLICATION_TYPE_SUNRISE) {
            throw new \InvalidArgumentException('Can send only sunrise applications!');
        }

        // to prevent accidental resubmission (there is a hard to find big with double submissions)
        if ($application->getApplicationStatus()->getId() === ApplicationStatus::SUCCESS) {
            return $application->getComment();
        }

        $send = function($token, $retry = false) use($submitData, $scholarship) {
            $options = [
                RequestOptions::VERIFY => is_production(),
                RequestOptions::HEADERS => [
                    'Authorization'     => "Bearer {$token}"
                ],
                (array_key_exists('data', $submitData) ? RequestOptions::FORM_PARAMS : RequestOptions::MULTIPART) =>
                    $submitData
            ];

            $client = $this->getHttpClient(['exceptions' => false]);

            $apiBaseUrl = trim(config('services.application.sunrise.api_base_url'), '/');

            $resp = $this->getHttpClient()->post(
                "{$apiBaseUrl}/scholarship/{$scholarship->getExternalScholarshipId()}/apply",
                $options
            );

            $statusCode = $resp->getStatusCode();
            if (!in_array($statusCode, [200, 201, 401]) ||
                ($retry && !in_array($statusCode, [200, 201]))) {

                $isDoubleSubmission = $statusCode === 422 &&
                     strpos($resp->getBody(), 'already applied for the scholarship') !== false;

                if (!$isDoubleSubmission) {
                    throw new \RuntimeException(sprintf(
                        "Failed to send application.\nStatus: %s\nError: %s\nMessage: %s\n",
                        $resp->getStatusCode(),
                        $resp->getReasonPhrase(),
                        $resp->getBody()
                    ));
                }
            }

            return $resp;
        };

        $resp = $send($this->getOauth2Token());
        if ($resp->getStatusCode() === 401) {
            $resp = $send($this->getOauth2Token(true), true);
        }

        $decodedResult = json_decode($resp->getBody()->getContents(), true);
        $application->setExternalApplicationId($decodedResult['data']['id']);
        \EntityManager::persist($application);

        return $resp->getBody()->getContents();
    }

    /**
     * @param bool $force pass True to discard cache and request a new token
     * @return mixed
     */
    protected function getOauth2Token($force = false)
    {
        if (!$force && $token = Cache::get('sunrise_oauth2_token')) {
            return $token;
        }

        $client = $this->getHttpClient(['exceptions' => false]);
        $config = config('services.application.sunrise');
        $resp = $client->post(
            $config['oauth2_url'],
            [
                RequestOptions::FORM_PARAMS => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $config['oauth2_client_id'],
                    'client_secret' => $config['oauth2_client_secret'],
                    'scope' => 'scholarships'
                ]
            ]
        );

        if ($resp->getStatusCode() !== 200) {
            throw new \RuntimeException(
                sprintf(
                    'Failed to obtain Sunrise oauth2 token. Response body: %s',
                     var_export($resp->getBody()->getContents(), true)
                )
            );
        }

        $data = json_decode($resp->getBody()->getContents(), true);
        $token = $data['access_token'];
        $expiresInSeconds = $data['expires_in'] - 60; // discard cache one minute before token expires

        Cache::put('sunrise_oauth2_token', $token, $expiresInSeconds);

        return $token;
    }

    /**
     * @param array $result
     * @param Scholarship $scholarship
     * @param Account $account
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function populateRequirements(array & $result, Scholarship $scholarship, Account $account) {
        $requirementsData = $scholarship->getApplicationImages($account) ?? [];

        /** @var ApplicationImage $item */
        foreach ($requirementsData as $item) {
            /** @var RequirementImage $requirement */
            $requirement = $item->getRequirement();
            $accountFile = $item->getAccountFile();
            $result[] = [
                'name' => "data[attributes][requirements][{$requirement->getExternalId()}]",
                'contents' => $accountFile->getFileContent(),
                'filename' => $accountFile->getRealName()
            ];
        }

        $requirementsData = $scholarship->getApplicationFiles($account) ?? [];
        /** @var ApplicationFile $item */
        foreach ($requirementsData as $item) {
            /** @var RequirementFile $requirement */
            $requirement = $item->getRequirement();
            $accountFile = $item->getAccountFile();
            $result[] = [
                'name' => "data[attributes][requirements][{$requirement->getExternalId()}]",
                'contents' => $accountFile->getFileContent(),
                'filename' => $accountFile->getRealName()
            ];
        }

        $requirementsData = $scholarship->getApplicationTexts($account) ?? [];
        /** @var ApplicationText $item */
        foreach ($requirementsData as $item) {
            /** @var RequirementText $requirement */
            $requirement = $item->getRequirement();
            $allowFiles = $requirement->getAllowFile();
            if ($allowFiles && ($accountFile = $item->getAccountFile())) {
                $result[] = [
                    'name' => "data[attributes][requirements][{$requirement->getExternalId()}]",
                    'contents' => $accountFile->getFileContent(),
                    'filename' => $accountFile->getRealName()
                ];
            } else {
                $requirementMap = $this->requirementNameMap();
                // according the Sunrise logic the following requirements mut be always sent as a file
                $forceFilesFor = [
                    $requirementMap['cv'],
                    $requirementMap['resume'],
                    $requirementMap['recommendation-letter'],
                ];

                if (in_array($requirement->getRequirementName()->getId(), $forceFilesFor)) {
                    $file = \DocumentGenerator::generate(
                        'pdf',
                        $requirement->getTitle(),
                        $item->getText()
                    );
                    $result[] = [
                        'name' => "data[attributes][requirements][{$requirement->getExternalId()}]",
                        'contents' => file_get_contents($file),
                        'filename' => $this->prepareFileName($requirement, $account, 'pdf')
                    ];
                } else {
                    $result[] = [
                        'name' => "data[attributes][requirements][{$requirement->getExternalId()}]",
                        'contents' => $item->getText(),
                    ];
                }
            }

        }

        $requirementsData = $scholarship->getApplicationInputs($account) ?? [];
        /** @var ApplicationInput $item */
        foreach ($requirementsData as $item) {
            /** @var RequirementInput $requirement */
            $requirement = $item->getRequirement();
            $result[] = [
                'name' => "data[attributes][requirements][{$requirement->getExternalId()}]",
                'contents' => $item->getText(),
            ];
        }

        $requirementsData = $scholarship->getApplicationSurvey($account) ?? [];
        /** @var ApplicationSurvey $item */
        foreach ($requirementsData as $item) {
            /** @var RequirementSurvey $requirement */
            $requirement = $item->getRequirement();
            $selectedOptionKeys = array_intersect(
                array_keys($requirement->getSurvey()[0]['options']), $item->getAnswers()[0]['options']
            );

            $resultOptions = implode(',', $selectedOptionKeys); // commas separated string of option ids (keys)
            $result[] = [
                'name' => "data[attributes][requirements][{$requirement->getExternalId()}]",
                'contents' => $resultOptions,
            ];
        }

        $requirementsData = $scholarship->getApplicationSpecialEligibility($account) ?? [];
        /** @var ApplicationSpecialEligibility $item */
        foreach ($requirementsData as $item) {
            /** @var RequirementSpecialEligibility $requirement */
            $requirement = $item->getRequirement();
            $result[] = [
                'name' => "data[attributes][requirements][{$requirement->getExternalId()}]",
                'contents' => (bool)$item->getVal(),
            ];
        }
    }
}
