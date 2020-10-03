<?php

namespace Adejorosam\LaravelFlutterwaveWebhook;

use Illuminate\Http\Request;
use Spatie\WebhookClient\Models\WebhookCall;
use Spatie\WebhookClient\WebhookConfig;
use Spatie\WebhookClient\WebhookProcessor;
use Spatie\WebhookClient\WebhookProfile\ProcessEverythingWebhookProfile;

class LaravelFlutterwaveWebhookController
{
    public function __invoke(Request $request, string $configKey = null)
    {
        $webhookConfig = new WebhookConfig([
            'name' => 'flutterwave',
            'signing_secret' => ($configKey) ?
                config('flutterwave-webhook.signing_secret_'.$configKey) :
                config('flutterwave-webhook.signing_secret'),
            'signature_header_name' => 'verif-hash',
            'signature_validator' => LaravelFlutterwaveSignatureValidator::class,
            'webhook_profile' => ProcessEverythingWebhookProfile::class,
            'webhook_model' => WebhookCall::class,
            'process_webhook_job' => config('flutterwave-webhook.model'),
        ]);

        (new WebhookProcessor($request, $webhookConfig))->process();

        return response()->json(['message' => 'ok']);
    }
}
