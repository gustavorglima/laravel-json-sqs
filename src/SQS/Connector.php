<?php

namespace GustavoLima\JsonSQS\SQS;

use Aws\Sqs\SqsClient;
use Illuminate\Support\Arr;
use Illuminate\Queue\Connectors\SqsConnector;

/**
 * Class Connector
 * @package GustavoLima\JsonSQS\SQS
 */
class Connector extends SqsConnector
{
    /**
     * Establish a queue connection.
     * @param array $config
     * @return Queue
     */
    public function connect(array $config)
    {
        $config = $this->getDefaultConfiguration($config);

        if ($config['key'] && $config['secret']) {
            $config['credentials'] = Arr::only($config, ['key', 'secret']);
        }

        return new Queue(
            new SqsClient($config), $config['queue'], Arr::get($config, 'prefix', '')
        );
    }
}