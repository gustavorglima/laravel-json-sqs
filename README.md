# Laravel Json SQS

### Dependencies

* PHP >= 5.5
* Laravel >= 5.4

### Installation
First of all you need to require the package using composer:
```
composer require gustavorglima/laravel-json-sqs
```

Now open `config/app.php` to add the provider:
```php
'providers' => [
    ...
    GustavoLima\JsonSQS\LaravelServiceProvider::class,
];
```