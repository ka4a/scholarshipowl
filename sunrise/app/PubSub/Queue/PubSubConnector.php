<?php namespace App\PubSub\Queue;

use Illuminate\Queue\Connectors\ConnectorInterface;
use Google\Cloud\PubSub\PubSubClient;

class PubSubConnector implements ConnectorInterface
{
    /**
     * @var PubSubClient
     */
    protected $client;

    /**
     * PubSubConnector constructor.
     * @param PubSubClient $client
     */
    public function __construct(PubSubClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $config
     * @return mixed
     */
    public function connect(array $config)
    {
        return new PubSubQueue($this->client, $config);
    }
}
