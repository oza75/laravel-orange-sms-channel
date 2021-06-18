<?php

namespace Oza75\OrangeSMSChannel\Tests;

use Oza75\OrangeSMSChannel\OrangeSMSServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        config()->set('services.orange', [
            'client_id' => 123456,
            'client_secret' => 456789
        ]);

        config()->set('mail.default', 'array');
    }

    protected function getPackageProviders($app): array
    {
        return [
            OrangeSMSServiceProvider::class,
        ];
    }
}