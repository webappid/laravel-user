<?php

namespace WebAppId\User\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use WebAppId\DDD\Traits\TestCaseTrait;
use WebAppId\User\ServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use TestCaseTrait;

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class
        ];
    }
}
