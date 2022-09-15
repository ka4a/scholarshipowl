<?php

namespace App\Submissions;

use App\Entity\Marketing\Submission;
use App\Services\Marketing\SubmissionService;
use Doctrine\ORM\EntityNotFoundException;
use Illuminate\Http\Response;

class GossamerSubmission extends AbstractSubmission
{
    const API_KEY = 'Iwx06OVrpB7gs2fgsoZNL5LeVZ5jB2CK8rGv4R0j';
    /**
     * @var SubmissionService
     */
    protected $ss;
    public function submissionSend($batch)
    {
        $submissionsGossamer = $this->ss->getSubmissionsAccountsByNameAndStatus(Submission::NAME_GOSSAMERSCIENCE,
            Submission::STATUS_INCOMPLETE, $batch);

        /** @var Submission $submission */
        foreach ($submissionsGossamer as $submission) {
            $submissionId = $submission->getSubmissionId();

            try {
                $data = json_decode($submission->getParams(), true);

                $this->send($data);

                $response = $this->getResponse();

                if ($this->hasErrors()) {
                    \CoregLogger::error($this->errors);

                    $error = implode(',', $this->errors);
                    if($response != ''){
                        $error = $error.' '.$response;
                    }

                    $this->ss->updateErrorSubmittionSubmission($submissionId, $error);
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
                $this->ss->updateErrorSubmittionSubmission($submissionId, $exc->getMessage());
            }
        }
    }

    /**
     * @param array $params
     * @return false|string
     */
    protected function setCurlOptions($data)
    {
        $this->curl->setOpt(CURLOPT_HTTPHEADER, array(
            'x-api-key: ' . self::API_KEY,
            'Content-Type: ' . 'application/json',
            'Content-Length: ' . strlen($data)
        ));
    }

    public function send($params = null)
    {
        $this->response = "";
        $this->rawResponse = "";
        $this->errors = array();

        $url = 'https://3eo08z80dk.execute-api.us-west-2.amazonaws.com/beta/rts';

        $data = json_encode($params);
        $this->setCurlOptions($data);

        \Log::info("SUBMITTED TO GossamerScience: " . $url);

        $this->curl->post($url, $data);

        $this->response = $this->curl->response;
        $this->rawResponse = $this->curl->rawResponse;

        if ($this->curl->error) {
            throw new \RuntimeException($this->curl->rawResponse);
        }

        return $this->onResponse();
    }

    public function onRequest($params = array())
    {
    }

    public function onResponse()
    {
        if ($this->curl->httpStatusCode == Response::HTTP_OK) {
            $response = json_decode($this->rawResponse, true);

            if (!is_array($response)) {
                $response = json_decode($response);
            }

            if (json_last_error() !== JSON_ERROR_NONE) {
                $response = json_decode(str_replace("'", '"', $this->response), true);
            }

            if (!isset($response['status'])) {
                $this->errors[] = var_export($response, true);
            }
        }
    }
}
