<?php

namespace GupshupPartner\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use GupshupPartner\GupshupPartnerServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            GupshupPartnerServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // set config values used by package
        $app['config']->set('gupshup.partner.email', env('GUPSHUP_PARTNER_EMAIL', 'test@example.com'));
        $app['config']->set('gupshup.partner.password', env('GUPSHUP_PARTNER_PASSWORD', 'testpassword'));
        $app['config']->set('cache.default', 'array');
    }
}
