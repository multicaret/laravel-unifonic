<p align="center">
<img src="http://cdn.multicaret.com/packages/assets/img/unifonic-logo.png">
</p>


# Laravel Unifonic 8.x +  
Start sending SMS with Unifonic right away using Laravel.


<p align="center">
<a href="https://packagist.org/packages/multicaret/laravel-unifonic"><img src="https://poser.pugx.org/multicaret/laravel-unifonic/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/multicaret/laravel-unifonic"><img src="https://poser.pugx.org/multicaret/laravel-unifonic/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/multicaret/laravel-unifonic"><img src="https://poser.pugx.org/multicaret/laravel-unifonic/license.svg" alt="License"></a>
</p>

---
## Installation

First, install the package through Composer.

```sh
$ composer require multicaret/laravel-unifonic
```

#### Laravel 5.5 and up

You don't have to do anything else, this package uses the package Auto-Discovery feature, and should be available as soon as you install it via Composer.


#### Laravel 5.4 and below

Then include the service provider inside `config/app.php`.

```php
'providers' => [
    ...
    Multicaret\Unifonic\UnifonicServiceProvider::class,
    ...
];
```
And add the alias as well

```php
'aliases' => [
    ...
    'Unifonic' => Multicaret\Unifonic\UnifonicFacade::class,
    ...
],
```


## Configurations
Head to [Dashboard](https://communication.cloud.unifonic.com/application) to create a new Application (check image below if you wish). Within in this app, you will find the `APP SID` copy it please. Now add these to your `config/services.php` file:

```
'unifonic' => [
    'app_id' => env('UNIFONIC_APP_ID'),
    'sender_id' => env('UNIFONIC_SENDER_ID'), // String, Optional
    'account_email' => env('UNIFONIC_ACCOUNT_EMAIL'),
    'account_password' => env('UNIFONIC_ACCOUNT_PASSWORD')
],
```


<p align="center">
<img src="http://cdn.multicaret.com/packages/assets/img/unifonic-app-sid-instructions.png">
</p>

 

Now Place these in your `.env` file.
```
UNIFONIC_APP_ID=
UNIFONIC_SENDER_ID=
UNIFONIC_ACCOUNT_EMAIL=
UNIFONIC_ACCOUNT_PASSWORD=
```

---
## Usage

#### Account related methods:
```php
// To test credentials and make sure the APP SID, email & password are set correctly. 
Unifonic::retrieveCredentialsForTesting();
```

#### Messages related methods:
```php
Unifonic::send(int $recipient, string $message, string $senderID = null);
Unifonic::getMessageIDStatus(int $messageID);
```

You may make asynchronous calls to Unifonic API, by prefixing your methods with the `async()` function:
```php

Unifonic::async(true); // async calls on, default value is true
Unifonic::async(false);// async calls off

// Later you can append the callback() to be executed when the response returns.
Unifonic::async()->callback(Callable $requestCallback); 

``` 


For more details about the parameters please refer to the [Api Documentation](https://developer.unifonic.com/) for more info, or read the [source code](https://github.com/multicaret/laravel-unifonic/blob/master/src/UnifonicClient.php).


### Contributing
See the [CONTRIBUTING](CONTRIBUTING.md) guide.

### Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information about what has changed recently.
