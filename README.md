# Handle Flutterwave Webhooks in a Laravel application

[![Latest Version on Packagist](https://img.shields.io/packagist/v/adejorosam/laravel-flutterwave-webhook.svg?style=flat-square)](https://packagist.org/packages/adejorosam/laravel-flutterwave-webhook)
[![Build Status](https://img.shields.io/travis/adejorosam/laravel-flutterwave-webhook/master.svg?style=flat-square)](https://travis-ci.org/adejorosam/laravel-flutterwave-webhook)
[![Quality Score](https://img.shields.io/scrutinizer/g/adejorosam/laravel-flutterwave-webhook.svg?style=flat-square)](https://scrutinizer-ci.com/g/adejorosam/laravel-flutterwave-webhook)
[![Total Downloads](https://img.shields.io/packagist/dt/adejorosam/laravel-flutterwave-webhook.svg?style=flat-square)](https://packagist.org/packages/adejorosam/laravel-flutterwave-webhook)

[Flutterwave](https://flutterwave.com/) can notify your application about various events via webhooks. This package can
help you handle those webhooks. It will automatically verify all incoming requests and ensure they are coming
from Flutterwave. 

Please note that this package will NOT handle what should be done after the request has been validated. You
should still write the code for that.

Find out more details about [Flutterwave's webhook here](https://developer.flutterwave.com/reference#webhook)

## Installation

You can install the package via composer:

```bash
composer require adejorosam/laravel-flutterwave-webhook
```

The service provider will automatically register itself.

You must publish the config file with:
```bash
php artisan vendor:publish --provider="adejorosam\LaravelFlutterwaveWebhook\LaravelFlutterwaveWebhookServiceProvider" --tag="config"
```

This is the contents of the config file that will be published at `config/flutterwave-webhooks.php`:

```php
return [

    /*
     * Flutterwave will sign each webhook using the secret hash you fielded. 
     * You can do that at the webhook configuration settings: https://dashboard.flutterwave.com/account/webhooks.
     */
    'signing_secret' => env('SECRET_HASH'),


    /*
     * The classname of the model to be used. The class should equal or extend
     * \Spatie\WebhookClient\ProcessWebhookJob.
    */
    'model' => Adejorosam\LaravelFlutterwaveWebhook\ProcessFlutterwaveWebhookJobs::class,,
];

```

In the `signing_secret` key of the config file you should add a valid webhook secret. You can find the secret used at [the webhook configuration settings on the flutterwave dashboard](https://dashboard.flutterwave.com/account/webhooks).

Next, you must publish the migration with:
```bash
php artisan vendor:publish --provider="Adejorosam\LaravelFlutterwaveWebhook\LaravelFlutterwaveServiceProvider" --tag="migrations"
```

After the migration has been published you can create the `webhook_calls` table by running the migrations:

```bash
php artisan migrate
```

Finally, take care of the routing: At [the flutterwave dashboard](https://dashboard.flutterwave.com/account/webhooks) you must configure at what url Flutterwave webhooks should hit your app. In the routes file of your app you must pass that route to `Route::flutterwaveWebhooks`:

```php
Route::flutterwaveWebhooks('webhook-route-configured-at-the-flutterwave-dashboard');
```

Behind the scenes this will register a `POST` route to a controller provided by this package. Because Flutterwave has no way of getting a csrf-token, you must add that route to the `except` array of the `VerifyCsrfToken` middleware:

```php
protected $except = [
    'webhook-route-configured-at-the-flutterwave-dashboard',
];
```

## Usage

``` php
// Usage description here
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email samsonadejoro@gmail.com instead of using the issue tracker.

## Credits

- [Samson Adejoro](https://github.com/adejorosam)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).