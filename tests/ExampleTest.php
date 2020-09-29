<?php

namespace Adejorosam\LaravelFlutterwaveWebhook\Tests;

use Orchestra\Testbench\TestCase;
use Adejorosam\LaravelFlutterwaveWebhook\LaravelFlutterwaveWebhookServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [LaravelFlutterwaveWebhookServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
