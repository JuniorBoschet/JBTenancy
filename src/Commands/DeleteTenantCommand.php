<?php

namespace MultiTenant\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use MultiTenant\Models\Tenant;


class DeleteTenantCommand extends Command
{
    protected $signature = 'tenant:delete {id} {--force}';
    protected $description = 'Delete a tenant and its database.';

    public function handle(): int
    {
        $id = $this->argument('id');


        $tenant = Tenant::find($id);
        if (!$tenant) {
            $this->error("Tenant with ID '$id' not found.");
            return Command::FAILURE;
        }
        
        $this->warn("This action will delete the tenant and its database. Are you sure? (yes/no)");

        if (!$this->confirm("Digite o nome do tenant para confirmar: '{$tenant->name}'")) {
            $this->info('Ação cancelada.');
            return Command::SUCCESS;
        }

        $this->deleteTenantDatabase($tenant->database);

        $tenant->delete();


        $this->info("Tenant with ID '$id' and its database have been deleted.");
        return Command::SUCCESS;
        
    }

    protected function deleteTenantDatabase(string $databaseName): void
    {
        Config::set("database.connections.landlord",
        [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', '127.0.0.1'),
            'port'      => env('DB_PORT', '3306'),
            'database'  => env('DB_DATABASE', 'landlord'),
            'username'  => env('DB_USERNAME', 'root'),
            'password'  => env('DB_PASSWORD', ''),
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]);

        DB::setDefaultConnection('landlord');
        DB::statement("DROP DATABASE IF EXISTS `$databaseName`");

     }
}