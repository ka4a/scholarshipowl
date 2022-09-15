<?php

namespace App\Services\PubSub;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Google\Cloud\PubSub\PubSubClient;
use ScholarshipOwl\Data\DateHelper;

class DigestService extends AbstractPubSubService
{
    const PUBSUB_TOPIC = 'sowl.digestTrigger';

    /**
     * DigestService constructor.
     * @param PubSubClient $pubSubClient
     */
    public function __construct(PubSubClient $pubSubClient)
    {
        $this->pubSubClient = $pubSubClient;
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
     * @param string $segmentAlias
     * @param string $templateName
     * @param $fields
     * @return mixed
     */
    public function triggerDigest(string $segmentAlias, string $templateName, array $fields, $mauticContactId = null)
    {
        $this->publishMessage(
            json_encode($fields),
            [
                'segmentAlias' => $segmentAlias,
                'templateName' => $templateName,
                'mauticContactId' => $mauticContactId
            ]
        );

        return $fields;
    }


    /**
     * publish message to PubSub
     *
     * @param string|null $data
     * @param array $attributes
     */
    protected function publishMessage(string $data = null, array $attributes = [])
    {
        $topic = $this->pubSubClient->topic(self::PUBSUB_TOPIC);

        $topic->publish([
            'data' => $data,
            'attributes' => $attributes
        ]);

        \Log::info(
            "trigger digest message sent to PubSub, data: {$data} attributes: ".json_encode($attributes)
        );
    }
}
