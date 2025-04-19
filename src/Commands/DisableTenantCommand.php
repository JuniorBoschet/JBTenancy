<?php

namespace MultiTenant\Commands;

use Illuminate\Console\Command;
use MultiTenant\Models\Tenant;

class DisableTenantCommand extends Command
{
    protected $signature = 'tenant:disable {id}';
    protected $description = 'Disable a tenant by ID';

    public function handle() : int
    {
        $idd = $this->argument('id');

        $tenant = Tenant::find($id);

        if (!$tenant) {
            $this->error("Tenant with ID {$id} not found.");
            return Command::FAILURE;
        }

        if ($tenant->status === false) {
            $this->info("Tenant with ID {$id} is already disabled.");
            return Command::SUCCESS;
        }

        $tenant->status = false; // Assuming 'status' is a boolean field
        $tenant->save();

        $this->info("Tenant with ID {$tenantId} has been disabled.");
        return Command::SUCCESS;
    }
}