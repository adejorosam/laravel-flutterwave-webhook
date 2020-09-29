<?php

namespace Adejorosam\LaravelFlutterwaveWebhook\Tests;

use Adejorosam\LaravelFlutterwaveWebhook\LaravelFlutterwaveWebhookServiceProvider;
use Orchestra\Testbench\TestCase;

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
