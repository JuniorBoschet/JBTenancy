<?php

namespace MultiTenant\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use MultiTenant\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class TenantMigrateCommand extends Command
{
    protected $signature = 'tenant:migrate {--tenant=}';
    protected $description = 'Run migrations for all tenant or a specific tenant.(--tenant=tenant_id)';

    public function handle(): int
    {
        $tenantId = $this->option('tenant');

       $tenants = $tenantId ? Tenant::where('id', $tenantId)->get() : Tenant::all();

        if ($tenants->isEmpty()) {
            $this->warn('No tenants found.');
            return Command::SUCCESS;
        }

        foreach ($tenants as $tenant) {
           $this->info("Running migrations for tenant: {$tenant->subdomain}");
    
            $this->configureTenantConnection($tenant);

            Artisan::call('migrate', [
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ]);

            $this->info(Artisan::output());
        }

        return Command::SUCCESS;
    }

    protected function configureTenantConnection(Tenant $tenant): void
    {
        $connectionName = 'tenant';

        Config::set("database.connections.$connectionName", [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', '127.0.0.1'),
            'port'      => env('DB_PORT', '3306'),
            'database'  => $tenant->database,
            'username'  => env('DB_USERNAME', 'root'),
            'password'  => env('DB_PASSWORD', ''),
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]);

        DB::setDefaultConnection($connectionName);
    }
}