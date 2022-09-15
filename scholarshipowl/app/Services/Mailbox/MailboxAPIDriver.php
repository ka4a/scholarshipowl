<?php

namespace App\Services\Mailbox;

use App\Contracts\GenericResponseContract;
use App\Extensions\GenericResponse;
use App\Http\Misc\Paginator;
use Google\Cloud\PubSub\PubSubClient;
use GuzzleHttp\Client;

class MailboxAPIDriver implements MailboxDriverInterface
{
    const PUBSUB_TOPIC_SENT_EMAILS = 'sowl.mailbox.saveSentEmail';
    const PUBSUB_TOPIC_READ_EMAILS = 'sowl.mailbox.markAsRead';

    /**
     * @var PubSubClient
     */
    protected $pubSubClient;

    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * @var array
     */
    protected $config;

    /**
     * DigestService constructor.
     * @param PubSubClient $pubSubClient
     */
    public function __construct(PubSubClient $pubSubClient)
    {
        $this->pubSubClient = $pubSubClient;
        $this->config = config('services.mailbox');
    }

    /**
     * @return PubSubClient
     */
    public function getPubSubClient()
    {
        return $this->pubSubClient;
    }

    /**
     * @param PubSubClient $client
     * @return $this
     */
    public function setPubSubClient(PubSubClient $client)
    {
        $this->pubSubClient = $client;

        return $this;
    }

    /**
     * @return Client
     */
    public function getHttpClient(array $config = [])
    {
        if ($this->httpClient === null) {
            $config['connect_timeout'] = 5;
            $config['read_timeout'] = 10;
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
     * @param Email $mail
     */
    public function saveSentEmail(Email $mail)
    {
        $fields = [
            'subject' => $mail->getSubject(),
            'body' => $mail->getBody(),
            'sender' => $mail->getSender(),
            'recipient' => $mail->getRecipient()
        ];

        $attributes = ['mailbox' => $mail->getMailbox()];

        if (!is_production()) {
            $attributes['instanceName'] = $this->instanceQueryParams()['query']['instanceName'];
        }

        $this->publishMessage(
            self::PUBSUB_TOPIC_SENT_EMAILS,
            json_encode($fields),
            $attributes
        );
    }

    /**
     * @param Email $mail
     */
    public function markAsRead(Email $mail)
    {
        $attributes = [
            'mailbox' => $mail->getMailbox(),
            'emailId' => (string)$mail->getEmailId(),
        ];

        if (!is_production()) {
            $attributes['instanceName'] = $this->instanceQueryParams()['query']['instanceName'];
        }

        $this->publishMessage(
            self::PUBSUB_TOPIC_READ_EMAILS,
            json_encode([]),
            $attributes
        );
    }

    /**
     * @param string $mailboxes
     * @return EmailCount[] Indexed by mailbox (username)
     */
    public function countEmails(array $mailboxes): GenericResponseContract
    {
        $params = $this->basicQueryParams();
        $params['query']['mailbox'] = implode(',', $mailboxes);
        $this->instanceQueryParams($params);

        $resp = $this->getHttpClient()->get(
            "{$this->config['api_base_url']}/countEmails",
            $params
        );

        $response = json_decode($resp->getBody()->getContents(), true);
        $data = [];
        foreach ($response['data'] as $mailbox => $item) {
            $data[$mailbox] = EmailCount::populate($item);
        }

        $response['data'] = $data;

        return GenericResponse::populate($response);
    }

    /**
     * @param string $mailbox
     * @param array $filter
     * @param Paginator|null $paginator
     * @return GenericResponseContract
     */
    public function fetchEmails(string $mailbox, array $filter = [], Paginator $paginator = null): GenericResponseContract
    {
        $params = $this->basicQueryParams();
        $params['query']['mailbox'] = $mailbox;

        $filter = array_filter($filter); // remove empty fields
        if ($filter) {
            $params['query']['filter'] = json_encode($filter);
        }

        $this->paginationQueryParams($params, $paginator);
        $this->instanceQueryParams($params);

        $resp = $this->getHttpClient()->get(
            "{$this->config['api_base_url']}/fetchEmails",
            $params
        );

        $data = json_decode($resp->getBody()->getContents(), true);

        $result = GenericResponse::populate($data);
        $resultData = [];
        foreach ($result->getData() as $k => $item) {
            $resultData[$k] = Email::populate($item);
        }

        $result->setData($resultData);

        return $result;
    }

    /**
     * @param array $mailboxes
     * @param array $filter
     * @param bool $group Group results by mailbox
     * @return array Email[] or if group = true then Email[[mailbox] => []]
     */
    public function fetchMultiple(array $mailboxes, array $filter = [], $group = false): array
    {
        $mailboxes = array_map('strtolower', $mailboxes);
        $params = $this->basicQueryParams();
        $params['query']['mailbox'] = implode(',', $mailboxes);

        $filter = array_filter($filter); // remove empty fields
        if ($filter) {
            $params['query']['filter'] = json_encode($filter);
        }

        $this->instanceQueryParams($params);

        $resp = $this->getHttpClient()->get(
            "{$this->config['api_base_url']}/fetchEmails",
            $params
        );

        $data = json_decode($resp->getBody()->getContents(), true);

        if ($group) {
            $result = array_fill_keys(array_values($mailboxes), null);
            foreach ($data['data'] as $k => $item) {
                $email = Email::populate($item);
                $result[$email->getMailbox()][] = $email;
            }
        } else {
            $result = [];
            foreach ($data['data'] as $k => $item) {
                $email = Email::populate($item);
                $result[] = $email;
            }
        }

        return $result;
    }

    /**
     * publish message to PubSub
     *
     * @param string|null $data
     * @param array $attributes
     */
    protected function publishMessage(string $topic, string $data = null, array $attributes = [])
    {
        $topic = $this->pubSubClient->topic($topic);

        $topic->publish([
            'data' => $data,
            'attributes' => $attributes
        ]);

        \Log::info(
            'Mailbox message sent to PubSub, attributes: '.json_encode($attributes)
        );
    }

    /**
     * @param array $params
     * @param Paginator|null $paginator
     */
    protected function paginationQueryParams(array & $params, Paginator $paginator = null)
    {
        if ($paginator) {
            $params['query']['page'] = $paginator->getPage();
            $params['query']['limit'] = $paginator->getLimit();
            $params['query']['offset'] = $paginator->getOffset();
        }
    }

    /**
     * @param array $params
     */
    protected function instanceQueryParams(array & $params = null)
    {
        if (!$params) {
            $params = [];
        }

        if (!is_production()) {
            if (\App::environment() === 'kubernetes') {
                $params['query']['instanceName'] = env('DB_DATABASE');
            } else { // if we happen to use this driver with local instance then use 'develop' instance DB
                $params['query']['instanceName'] = 'develop';
            }
        }

        return $params;
    }

    /**
     * @return array
     */
    protected function basicQueryParams()
    {
        $params = [
            'query' => [
                'api_key' => $this->config['api_key']
            ]
        ];

        return $params;
    }
}