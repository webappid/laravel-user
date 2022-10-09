<?php
/**
 * Created by PhpStorm.
 * UserParam: dyangalih
 * Date: 2019-01-25
 * Time: 11:53
 */

namespace WebAppId\User;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use WebAppId\User\Commands\SeedCommand;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->commands(SeedCommand::class);
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'user');
        $this->publishes([
            __DIR__ . '/config' => base_path('config')
        ]);
        $this->mergeConfigFrom(__DIR__ . '/config/laravel_user.php', 'laravel_user');
    }
}