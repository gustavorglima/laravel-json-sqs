<?php

namespace GustavoLima\JsonSQS;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class Dispatcher
 * @package GustavoLima\JsonSQS
 */
class Dispatcher implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /** @var array */
    protected $data;

    /** @var string */
    protected $job;

    /**
     * DispatchedJob constructor.
     * @param $data
     */
    public function __construct($job, array $data)
    {
        $this->onConnection(config('json-sqs.default_connection'));
        $this->data = $data;
        $this->job = $job;
    }

    /**
     * Get JSON payload string
     * @return string
     */
    public function payload()
    {
        return json_encode([
            'job' => $this->job,
            'data' => $this->data
        ]);
    }
}