<?php

namespace GustavoLima\JsonSQS\SQS;

use Illuminate\Queue\Jobs\SqsJob;
use Illuminate\Queue\Jobs\JobName;

class Job extends SqsJob
{
    /**
     * Process an exception that caused the job to fail.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function failed($e)
    {
        $this->markAsFailed();

        $payload = $this->payload();

        list($class, $method) = JobName::parse($payload['job']);

        if (method_exists($this->instance = $this->resolve($class), 'failed')) {
            $this->instance->failed($payload['data'], $e);
        } else {
            $body = json_decode($this->job["Body"]);
            $body->job = array_search(str_replace('@handle', '', $payload['job']), config('json-sqs.handlers'));
            $this->job["Body"] = json_encode($body);
        }
    }
}