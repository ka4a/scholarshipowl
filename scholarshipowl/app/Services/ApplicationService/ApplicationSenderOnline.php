<?php namespace App\Services\ApplicationService;

use App\Entity\Account;
use App\Entity\Application;
use App\Entity\ApplicationInput;
use App\Entity\Contracts\ApplicationRequirementContract;

use App\Entity\ApplicationText;
use App\Entity\ApplicationFile;
use App\Entity\ApplicationImage;
use App\Entity\Scholarship;
use App\Entity\Form;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Symfony\Component\HttpFoundation\File\File;

class ApplicationSenderOnline extends ApplicationSenderAbstract
{

    const REQUEST_FILES = '__request_multipart';

    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * @return Client
     */
    public function getHttpClient()
    {
        if ($this->httpClient === null) {
            $this->httpClient = new Client();
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
    public function prepareSubmitData(Scholarship $scholarship, Account $account) : array
    {
        $params = [self::REQUEST_FILES => []];
        $applicationTexts = $scholarship->getApplicationTexts($account);
        $applicationFiles = $scholarship->getApplicationFiles($account);
        $applicationImages = $scholarship->getApplicationImages($account);
        $applicationInputs = $scholarship->getApplicationInputs($account);

        foreach ($scholarship->getForms() as $form) {
            switch ($form->getSystemField()) {
                case Form::TEXT:
                    /** @var ApplicationText $applicationText */
                    $applicationText = $this->findApplicationRequirementByForm($applicationTexts, $form);

                    $params[$form->getFormField()] = $applicationText->getText();
                    break;
                case Form::REQUIREMENT_UPLOAD_TEXT:
                    /** @var ApplicationText $applicationText */
                    $applicationText = $this->findApplicationRequirementByForm($applicationTexts, $form);

                    $params[self::REQUEST_FILES][] = $this->prepareMultipart(
                        $form->getFormField(),
                        $this->getApplicationTextFile($applicationText),
                        $this->prepareFileName($applicationText->getRequirement(), $account,  $applicationText->getRequirement()->getAttachmentType())
                    );
                    break;
                case Form::REQUIREMENT_UPLOAD_FILE:
                    /** @var ApplicationFile $applicationFile */
                    $applicationFile = $this->findApplicationRequirementByForm($applicationFiles, $form);
                    if (null === ($accountFile = $applicationFile->getAccountFile())) {
                        throw new \LogicException('Application file missing assigned file!');
                    }

                    $params[self::REQUEST_FILES][] = $this->prepareMultipart(
                        $form->getFormField(),
                        $accountFile->getFileContent()
                    );
                    break;
                case Form::REQUIREMENT_UPLOAD_IMAGE:
                    /** @var ApplicationImage $applicationImage */
                    $applicationImage = $this->findApplicationRequirementByForm($applicationImages, $form);
                    if (null === ($accountFile = $applicationImage->getAccountFile())) {
                        throw new \LogicException('Application image missing assigned file!');
                    }

                    $params[self::REQUEST_FILES][] = $this->prepareMultipart(
                        $form->getFormField(),
                        $accountFile->getFileContent()
                    );
                    break;
                case Form::INPUT:
                    /** @var ApplicationInput $applicationInput */
                    $applicationInput = $this->findApplicationRequirementByForm($applicationInputs, $form);

                    $params[$form->getFormField()] = $applicationInput->getText();
                    break;
                default:
                    $params[$form->getFormField()] = Form::mapField($form, $account);
                    break;
            }
        }

        return $params;
    }

    /**
     * @param Scholarship $scholarship
     * @param array $submitData
     * @param Application $application
     * @return mixed|null|string|string[]
     */
    public function sendApplication(Scholarship $scholarship, array $submitData, Application $application)
    {
        if ($scholarship->getApplicationType() !== Scholarship::APPLICATION_TYPE_ONLINE) {
            throw new \InvalidArgumentException('Can send only online applications!');
        }

        $options = [
            RequestOptions::VERIFY => false,
            RequestOptions::HEADERS => [
                'Accept'     => '*/*',
                'User-Agent' => "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36",
            ],
        ];

        switch ($scholarship->getFormMethod()) {
            case Scholarship::FORM_METHOD_POST:
                $requestFiles = $submitData[self::REQUEST_FILES] ?? [];
                unset($submitData[self::REQUEST_FILES]);

                if (!empty($requestFiles)) {
                    $options[RequestOptions::MULTIPART] = $this->prepareMultiParts($requestFiles, $submitData);
                } else {
                    $options[RequestOptions::FORM_PARAMS] = $submitData;
                }

                $response = $this->getHttpClient()->post($scholarship->getFormAction(), $options);
                break;
            case Scholarship::FORM_METHOD_GET:
                if (isset($submitData[self::REQUEST_FILES])) {
                    unset($submitData[self::REQUEST_FILES]);
                }
                $options += [RequestOptions::QUERY => $submitData];
                $response = $this->getHttpClient()->get($scholarship->getFormAction(), $options);
                break;
            default:
                throw new \LogicException('Unknown form method: %s', $scholarship->getFormMethod());
        }

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException(sprintf(
                "Failed request to send application.\nStatus: %s\nError: %s\nMessage: %s\n",
                $response->getStatusCode(),
                $response->getReasonPhrase(),
                $response->getBody()
            ));
        }

        return preg_replace("/<script\b[^>]*>(.*?)<\/script>/is", "<JS>/* $1 */</JS>", $response->getBody());
    }

    /**
     * @param string      $field
     * @param mixed       $content
     * @param string|null $filename
     *
     * @return array
     */
    public function prepareMultipart($field, $content, string $filename = null)
    {
        $multipart = [
            'name' => $field,
            'contents' => $content,
        ];

        if ($filename) {
            $multipart['filename'] = $filename;
        }

        return $multipart;
    }

    /**
     * @param array $requestFiles
     * @param array $submitData
     *
     * @return array
     */
    protected function prepareMultiParts(array $requestFiles, array $submitData)
    {
        $multipart = [];

        foreach ($requestFiles as $filePart) {
            if (isset($filePart['contents'])) {
                $multipart[] = $filePart;
            }
        }

        foreach ($submitData as $field => $data) {
            $multipart[] = $this->prepareMultipart($field, $data);
        }

        return $multipart;
    }

    /**
     * @param ApplicationRequirementContract[] $applicationRequirements
     * @param Form                             $form
     *
     * @return ApplicationRequirementContract
     */
    protected function findApplicationRequirementByForm($applicationRequirements, Form $form)
    {
        foreach ($applicationRequirements as $applicationRequirement) {
            if ($applicationRequirement->getRequirement()->getId() === (int) $form->getValue()) {
                return $applicationRequirement;
            }
        }

        throw new \LogicException('Application requirement can\'t be found!');
    }
}
