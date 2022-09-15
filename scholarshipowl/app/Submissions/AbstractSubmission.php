<?php

/**
 * AbstractSubmission
 *
 * @package     ScholarshipOwl\Net\Submissions
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created        23. July 2015.
 * @copyright    Sirio Media
 */

namespace App\Submissions;

use App\Services\Marketing\CoregService;
use App\Services\Marketing\SubmissionService;
use \Curl\Curl;


abstract class AbstractSubmission
{
    /** @var mixed $url */
    protected $url;
    protected $method;
    protected $auth;
    protected $options;

    protected $curl;
    protected $response;
    protected $rawResponse;
    protected $errors;

    abstract function onRequest($params = array());

    abstract function onResponse();

    protected $ss;
    protected $cs;

    public function __construct($submissionName)
    {
        $this->url = config("scholarshipowl.submission." . $submissionName . ".url");
        $this->method = config("scholarshipowl.submission." . $submissionName . ".method");
        $this->auth = config("scholarshipowl.submission." . $submissionName . ".auth");
        $this->options = config("scholarshipowl.submission." . $submissionName . ".options");
        $this->ss = app(SubmissionService::class);
        $this->cs = app(CoregService::class);

        $this->curl = new Curl();
        $this->curl->setOpt(CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1");
        $this->curl->setOpt(CURLOPT_HEADER, false);
        $this->curl->setOpt(CURLOPT_RETURNTRANSFER, true);
        $this->curl->setOpt(CURLOPT_FOLLOWLOCATION, 0);

        $this->response = "";
        $this->rawResponse = "";
        $this->errors = array();
    }

    public function send($params = null)
    {
        $this->response = "";
        $this->rawResponse = "";
        $this->errors = array();

        $params = $this->onRequest($params);

        if ($this->method == "GET") {
            $this->curl->get($this->getUrl(), $params);

            $this->response = $this->curl->response;
            $this->rawResponse = $this->curl->rawResponse;
        } else {
            if ($this->method == "POST") {
                $this->curl->post($this->getUrl(), $params);

                $this->response = $this->curl->response;
                $this->rawResponse = $this->curl->rawResponse;
            } else {
                throw new \RuntimeException("Unknown http request method");
            }
        }

        if ($this->curl->error) {
            throw new \RuntimeException($this->curl->rawResponse, $this->curl->curlErrorCode);
        }

        return $this->onResponse();
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function getAuth()
    {
        return $this->auth;
    }

    public function setAuth($auth)
    {
        $this->auth = $auth;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function hasErrors()
    {
        return count($this->errors) > 0;
    }
}
