<?php

namespace MultiTenant\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use MultiTenant\Http\Middleware\IdentifyTenant;

use MultiTenant\Commands\CreateTenantCommand;
use MultiTenant\Commands\MakeTenantMigrationCommand;
use MultiTenant\Commands\TenantMigrateCommand;
use MultiTenant\Commands\MakeTenantSeederCommand;
use MultiTenant\Commands\DeleteTenantCommand;
use MultiTenant\Commands\DisableTenantCommand;

class MultiTenantServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->mergeConfirmFrom(__DIR__ . '/../../config/multi-tenant.php', 'multi-tenant');

        $this->publishes([
            __DIR__ . '/../../config/multi-tenant.php' => config_path('multi-tenant.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        if($this->app->runningInConsole()){
            $this->commands([
                CreateTenantCommand::class,
                MakeTenantMigrationCommand::class,
                TenantMigrateCommand::class,
                MakeTenantSeederCommand::class,
                DeleteTenantCommand::class,
                DisableTenantCommand::class,
            ]);
        }
        
    }
   
    public function boot()
    {
        $this->app['router']->aliasMiddleware('tenant.identify', IdentifyTenant::class);

        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');

        $this->publishes([
            __DIR__ . '/../../database/seeders' => database_path('seeders/tenant'),
        ], 'seeders');	
    }

   
}