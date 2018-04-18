# Laravel Json SQS

### Dependencies

* PHP >= 5.5
* Laravel >= 5.4

### Installation
First of all you need to require the package using composer:
```
composer require gustavorglima/laravel-json-sqs
```


### Configuration
Now open `config/app.php` to add the provider:
```php
'providers' => [
    ...
    GustavoLima\JsonSQS\LaravelServiceProvider::class,
];
```

Publish `config/json-sqs.php`
```php
php artisan vendor:publish --provider="GustavoLima\JsonSQS\LaravelServiceProvider"
```

Create failed table:
```
php artisan queue:failed-table
```

Create `json-sqs` connection on `config/queue.php`:
```
'json-sqs' => [
    'driver' => 'json-sqs',
    'key' => env('SQS_KEY'),
    'secret' => env('SQS_SECRET'),
    'prefix' => env('SQS_PREFIX'),
    'queue' => env('SQS_QUEUE'),
    'region' => env('SQS_REGION'),
],
```

Set queue driver on `.env`
```
QUEUE_DRIVER=json-sqs
```

And configure your credentials (Example):
```
SQS_KEY=ABCDEFGHIJKLMN
SQS_SECRET=abCDEFghiJKlmNOpqRStuVWXyzabCDEFghiJK
SQS_PREFIX=https://sqs.us-east-1.amazonaws.com/1234567890
SQS_QUEUE=sqs-queue-name
SQS_REGION=us-east-1
```
### Creating a Job
To read published messages on SQS Queue we need to create a Job:
```
php artisan make:job TestJob
```

You handle method must be like that:
```
public function handle(Job $job, $data)
{
    dd($data); // Just to debug received message
}
```

Open `config/json-sqs.php` and add:
```
'test-job' => App\Jobs\TestJob::class,
```

### Publishing a message
To publish a message it's pretty simple, you just need to pass the job name that was specified in `config/json-sqs.php` and the data, example:
```
dispatch(
    new Dispatcher('test-job', [
        'some' => 'data'
    ])
);
```

## Start the worker
```
php artisan queue:work json-sqs --tries=3
```

Obs. In production we recommend to use [Supervisor](http://supervisord.org/).