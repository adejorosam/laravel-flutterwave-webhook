# Handle Flutterwave Webhooks in a Laravel application

[![Latest Version on Packagist](https://img.shields.io/packagist/v/adejorosam/laravel-flutterwave-webhook.svg?style=flat-square)](https://packagist.org/packages/adejorosam/laravel-flutterwave-webhook)
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
php artisan vendor:publish --provider="Adejorosam\LaravelFlutterwaveWebhook\LaravelFlutterwaveWebhookServiceProvider" --tag="config"
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

Flutterwave will send out webhooks for several event types. You can find the [full list of events types] in the Flutterwave documentation.

Flutterwave will sign all requests hitting the webhook url of your app. This package will automatically verify if the signature is valid. If it is not, the request was probably not sent by Flutterwave.

Unless something goes terribly wrong, this package will always respond with a `200` to webhook requests. Sending a `200` will prevent Flutterwave from resending the same event over and over again. All webhook requests with a valid signature will be logged in the `webhook_calls` table. The table has a `payload` column where the entire payload of the incoming webhook is saved.

If the signature is not valid, the request will not be logged in the `webhook_calls` table but a `Adejorosam\LaravelWebhooks\WebhookFailed` exception will be thrown.
If something goes wrong during the webhook request the thrown exception will be saved in the `exception` column. In that case the controller will send a `500` instead of `200`.

### Storing and processing webhooks

After the signature is validated and the webhook profile has determined that the request should be processed, the package will store and process the request.

The request will first be stored in the `webhook_calls` table. This is done using the `WebhookCall` model.

Should you want to customize the table name or anything on the storage behavior, you can let the package use an alternative model. A webhook storing model can be specified in the `webhook_model`. Make sure you model extends `Spatie\WebhookClient\Models\WebhookCall`.

You can change how the webhook is stored by overriding the `storeWebhook` method of `WebhookCall`. In the `storeWebhook` method you should return a saved model.

Next, the newly created `WebhookCall` model will be passed to a queued job that will process the request. Any class that extends `\Spatie\WebhookClient\ProcessWebhookJob` is a valid job. Here's an example:

```php
<?php
namespace Adejorosam\LaravelFlutterwaveWebhook;

use \Spatie\WebhookClient\ProcessWebhookJob;

//The class extends "ProcessWebhookJob" class as that is the class
//that will handle the job of processing our webhook before we have
//access to it.


class ProcessFlutterwaveWebhook extends ProcessWebhookJob
{
    public function handle()
    {
        $data = json_decode($this->webhookCall, true);
        //Do something with great with this!
       http_response_code(200); //Acknowledge you received the response
    }
}
```

You should specify the class name of your job in the `process_webhook_job` of the `webhook-client` config file.


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
