<?php

namespace WebAppId\User\Tests;

use Faker\Factory as Faker;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Artisan;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    private $faker;
    
    protected $prefix_route = "/test";
    
    private $container;
    
    /**
     * Set up the test
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->loadMigrationsFrom([
            '--realpath' => realpath(__DIR__ . '/../src/migrations'),
        ]);
        $this->artisan('webappid:user:seed');
        
    }
    
    protected function getFaker()
    {
        if ($this->faker == null) {
            $this->faker = new Faker;
        }
        
        return $this->faker->create('id_ID');
    }
    
    protected function getPackageProviders($app)
    {
        return [
            \WebAppId\User\ServiceProvider::class
        ];
    }
    
    public function tearDown():void
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
    
    protected function getContainer()
    {
        if ($this->container == null) {
            $this->container = new Container();
        }
        return $this->container;
    }
    
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:'
        ]);
    }
}
