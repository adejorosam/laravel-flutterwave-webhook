<?php

/*
 * You can place your custom package configuration in here.
 */

return [

    
    /*
     * Flutterwave will sign each webhook using a secret. You can find the used secret at the
     * webhook configuration settings: https://dashboard.flutterwave.com/dashboard/settings/webhooks.
     */

    'signing_secret' => env('SECRET_HASH'),

    /*
     * The classname of the model to be used. The class should equal or extend
     * \Spatie\WebhookClient\ProcessWebhookJob.
    */
    'model' => ''

    
];