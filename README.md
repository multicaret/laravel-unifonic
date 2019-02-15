<p align="center">
<img src="http://unifonic.com/wp-content/uploads/2016/08/logo-1.png">
</p>


# Laravel Unifonic 5.x 
Start sending SMS and making Voice calls with Unifonic right away using Laravel.


<p align="center">
<a href="https://packagist.org/packages/liliom/laravel-unifonic"><img src="https://poser.pugx.org/liliom/laravel-unifonic/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/liliom/laravel-unifonic"><img src="https://poser.pugx.org/liliom/laravel-unifonic/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/liliom/laravel-unifonic"><img src="https://poser.pugx.org/liliom/laravel-unifonic/license.svg" alt="License"></a>
</p>

---
## Installation

First, install the package through Composer.

```sh
$ composer require liliom/laravel-unifonic
```

#### Laravel 5.5 and up

You don't have to do anything else, this package uses the Package Auto-Discovery feature, and should be available as soon as you install it via Composer.

#### Laravel 5.4 and down

Then include the service provider inside `config/app.php`.

```php
'providers' => [
    ...
    Liliom\Unifonic\UnifonicServiceProvider::class,
    ...
];
```
And add the alias as well

```php
'aliases' => [
    ...
    'Unifonic' => Liliom\Unifonic\UnifonicFacade::class,
    ...
],
```

## Configurations
Add to your `config/services.php` a new array as the following
```
'unifonic' => [
    'app_id' => env('UNIFONIC_APP_SID'),
    'sender_id' => env('UNIFONIC_SENDER_ID') //optional
],
```

If you don't want to publish the configs add Unifonic App Sid within your `.env` file

```
UNIFONIC_APP_SID={YOUR_DEFAULT_APP_ID}
```

---
## Usage

#### Account related methods:
```php
Unifonic::getBalance();
Unifonic::addSenderID(string $senderID);

// To test credentials and make sure the APP ID is configured correctly. 
Unifonic::testCredentials();
```

#### Messages related methods:
```php
Unifonic::send(int $recipient, string $message, string $senderID = null);
Unifonic::sendBulk(array $recipients, string $message, string $senderID = null);
Unifonic::getMessageIDStatus(int $messageID);
Unifonic::getMessagesReport($dateFrom = null, $dateTo = null, string $senderId = null, string $status = null, string $delivery = null);
```

You may make asynchronous calls to Unifonic API, by prefixing your methods with the `async()` function:
```php

Unifonic::async(true) // async calls on, default value is true
Unifonic::async(false) // async calls off

// Later you can append the callback() to be executed when the response returns.
Unifonic::async()->callback(Callable $requestCallback) 

``` 


For more details about the parameters please refer to the [Api Documentation](http://docs.unifonic.apiary.io/) for more info, or read the [source code](https://github.com/liliomlab/laravel-unifonic/blob/master/src/UnifonicClient.php).


### Contributing
See the [CONTRIBUTING](CONTRIBUTING.md) guide.

### Change Log
See the [log](CHANGELOG.md) file.
