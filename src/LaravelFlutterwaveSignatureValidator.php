<?php

namespace Adejorosam\LaravelFlutterwaveWebhook;

use Exception;
use Illuminate\Http\Request;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Spatie\WebhookClient\WebhookConfig;

class LaravelFlutterwaveSignatureValidator implements SignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        $signature = $request->header('verif-hash');
        $secret = $config->secret_hash;

        try {
            // Webhook::constructEvent($request->getContent(), $signature, $secret);
            if($signature === $secret){
                return true;
            }
            
        } catch (Exception $exception) {
            return false;
        }

        // return true;
    }
}