<?php
namespace App\Services\PubSub;

abstract class AbstractPubSubService{

    /**
     * publish message to PubSub
     *
     * @param string|null $data
     * @param array $attributes
     */
    protected abstract function publishMessage(string $data = null, array $attributes = []);
}