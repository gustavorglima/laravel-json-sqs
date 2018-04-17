<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Connection
    |--------------------------------------------------------------------------
    |
    | This option controls the default SQS connection that will be used when 
    | is not specified a connection.
    |
    */
    'default_connection' => env('QUEUE_DRIVER', 'json-sqs'),

    /*
    |--------------------------------------------------------------------------
    | Job Handlers
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the handlers that will be used on your
    | application Samples of each available type of connection are provided
    | inside this array.
    |
    */
    'handlers' => [
        
    ]
];