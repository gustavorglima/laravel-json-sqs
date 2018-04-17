<?php

namespace GustavoLima\JsonSQS\SQS;

use Illuminate\Support\Arr;
use Illuminate\Queue\SqsQueue;
use Illuminate\Queue\Jobs\SqsJob;
use GustavoLima\JsonSQS\Dispatcher;

/**
 * Class CustomSqsQueue
 * @package App\Services
 */
class Queue extends SqsQueue
{
    /**
     * Create a payload string from the given job
     * @param string $job
     * @param string $data
     * @return string
     */
    protected function createPayload($job, $data = '')
    {
        if (!$job instanceof Dispatcher) {
            return parent::createPayload($job, $data);
        }

        return $job->payload();
    }

    /**
     * Pop the next job off of the queue.
     * @param  string $queue
     * @return \Illuminate\Contracts\Queue\Job|null
     */
    public function pop($queue = null)
    {
        $queueUrl = $this->getQueue($queue);
        $response = $this->sqs->receiveMessage([
            'QueueUrl' => $queueUrl,
            'AttributeNames' => [
                'ApproximateReceiveCount'
            ]
        ]);

        $messages = Arr::get($response, 'Messages', []);

        if (count($messages)) {
            return new Job(
                $this->container, $this->sqs, $this->payload(Arr::first($messages)), $this->connectionName, $queueUrl
            );
        }
    }

    /**
     * Create right payload
     * @param array $message
     * @return array
     * @throws \Exception
     */
    private function payload(array $message)
    {
        $body = json_decode($message['Body']);
        $body->job = $this->handler($body->job);
        $message['Body'] = json_encode($body);
        return $message;
    }

    /**
     * Get Job Handler
     * @param $job
     * @return mixed
     * @throws \Exception
     */
    private function handler($job)
    {
        $handler = Arr::get(config('json-sqs.handlers'), $job, null);

        if (!$handler) {
            if (array_search(str_replace('@handle', '', $job), config('json-sqs.handlers'))) {
                return $job;
            }

            throw new \Exception("Job handler not found for job '{$job}'!");
        }

        return sprintf('%s@handle', $handler);
    }
}
