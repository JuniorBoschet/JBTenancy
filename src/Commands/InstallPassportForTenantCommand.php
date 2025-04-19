<?php

namespace MultiTenant\Commands;

use Illuminate\Console\Command;
use MultiTenant\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use MultiTenant\Support\TenantManager;

class InstallPassportForTenantCommand extends Command
{
    protected $signature = 'tenant:install-passport {--tenant=}';

    protected $description = 'Install Passport for a tenant or all tenants.';

    public function handle(): int
    {
        $tenantId= $this->option('tenant');

        $tenants = $tenantId ?
        [Tenant::find($tenantId)] : Tenant::all();

        Artisan::call('passport:install', ['--force' => true]);

        foreach ($tenants as $tenant) {
            if (!$tenant) continue;

            $this->info("Installing Passport for tenant: {$tenant->id}");

            
            TenantManager::activate($tenant);

            // Run the Passport installation commands
            Artisan::call('migrate', [
                '--path' => 'vendor/laravel/passport/database/migrations',
                '--force' => true,
            ]);
            $this->info(Artisan::output());

        }

        return Command::SUCCESS;
    }
}