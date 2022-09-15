<?php
namespace App\Queue\Connectors;

use App\Queue\PubSubQueue;
use Google\Cloud\PubSub\PubSubClient;
use Illuminate\Queue\Connectors\ConnectorInterface;

class PubSubConnector implements ConnectorInterface
{
    /**
     * @param array $config
     * @return mixed
     */
    public function connect(array $config)
    {
        return new PubSubQueue(new PubSubClient($config), $config);
    }
}