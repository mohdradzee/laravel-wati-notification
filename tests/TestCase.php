<?php

namespace mohdradzee\WatiNotification\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use mohdradzee\WatiNotification\WatiServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            WatiServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Set up environment configurations
        $app['config']->set('wati.api_token', 'test-token');
        $app['config']->set('wati.api_url', 'https://wati.test/api');
    }
}
