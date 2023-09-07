## Bug Notification for Laravel
[![Software License](https://poser.pugx.org/rabbit-digital/bugtify-laravel/license.svg)](LICENSE.md)
[![Latest Version on Packagist](https://poser.pugx.org/rabbit-digital/bugtify-laravel/v/stable.svg)](https://packagist.org/packages/rabbit-digital/bugtify-laravel)
[![Total Downloads](https://poser.pugx.org/rabbit-digital/bugtify-laravel/d/total.svg)](https://packagist.org/packages/rabbit-digital/bugtify-laravel)

## What is Bugtify?
This package allows you to send error log to Discord via webhook. Allow you to track any error from your Laravel Application

## Installation on Laravel

The Bugtify can be installed with [Composer](https://getcomposer.org/). Run this command:

```sh
composer require rabbit-digital/bugtify-laravel
```

Register our service provider in providers array in `config/app.php` before your `AppServiceProvider::class`

```php
'providers' => [
    // ...
    RabbitDigital\Bugtify\BugtifyServiceProvider::class,
    // ...
],
```

You just need to publish the config file of the package.

```sh
php artisan vendor:publish --provider="RabbitDigital\Bugtify\BugtifyServiceProvider" --tag=config
```

Configure your Discord Webhook URL in your .env file:
```dotenv
BUGTIFY_DISCORD_WEBHOOK=your-discord-webhook-here
```

If you need to avoid to send notification with the same error can configure with option below
```dotenv
BUGTIFY_LIMIT_ENABLED=true
```

## Installation on Lumen
The Bugtify can be installed with [Composer](https://getcomposer.org/). Run this command:

```sh
composer require rabbit-digital/bugtify-laravel
```

Copy the config file (bugtify.php) to lumen config directory.
```sh
php -r "file_exists('config/') || mkdir('config/'); copy('vendor/rabbit-digital/bugtify-laravel/config/bugtify.php', 'config/bugtify.php');"
```
And adjust config file (config/bugtify.php) with your desired settings.

In bootstrap/app.php you will need to:

Uncomment this line if you still not did it.
```php 
$app->withFacades();
```

Register Bugtify service provider

```php 
$app->register(RabbitDigital\Bugtify\BugtifyServiceProvider::class);
```

## Reporting unhandled exceptions

To ensure all unhandled exceptions are sent to Discord, set up a bugtify logging channel and add it to your logging stack in config/logging.php:

```php
'channels' => [
        'stack' => [
            'driver' => 'stack',
            // Add bugtify to the stack:
            'channels' => ['single', 'bugtify'],
        ],

        // ...

        // Create a bugtify logging channel:
        'bugtify' => [
            'driver' => 'bugtify',
        ],
    ],
```

## Laravel and Lumen version below 5.5
Laravel and Lumen version less than 5.5 not support Logger class. Need to manually add in `App\Exception\Handler.php`
```php
public function report(Exception $e)
{
    \RabbitDigital\Bugtify\Facades\Bugtify::notifyException($e);

    parent::report($e);
}
```

## Reporting handled exceptions

Reporting handled exceptions can be accomplished as follows:

```php
try {
    // Add some error code here
} catch (Exception $ex) {
    \RabbitDigital\Bugtify\Facades\Bugtify::notifyException($ex);
}
```

## License

The Bugtify for Laravel library is free software released under the MIT License. See [LICENSE](LICENSE) for details.
