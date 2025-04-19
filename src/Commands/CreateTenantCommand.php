<?php

namespace MultiTenant\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use MultiTenant\Models\Tenant;
use Illuminate\Support\Str;

class CreateTenantCommand extends Command
{
    protected $signature = 'tenant:create {name} {subdomain}';
    protected $description = 'Create a new tenant with the given name, subdomain';

    public function handle(): int
    {
        $name = $this->argument('name');
        $subdomain = strtolower($this->argument('subdomain'));
        $dbName = config('multi-tenant.database.prefix', 'tenant_') . $subdomain;

        if(Tenant::where('subdomain', $subdomain)->exists()){
            $this->error("Tenant with subdomain '$subdomain' already exists.");
            return Command::FAILURE;
        }

        if(Tenant::where('database', $dbName)->exists()){
            $this->error("Database with name '$dbName' already exists.");
            return Command::FAILURE;
        }

        DB::statement("CREATE DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        Tenant::create([
            'name' => $name,
            'subdomain' => $subdomain,
            'database' => $database,
        ]);

        $this->info("Tenant '$name' with subdomain '$subdomain' and database '$database' created successfully.");
    
        config([
            'database.connections.tenant'=> 
            [
                'driver' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => $dbName,
                'username'  => env('DB_USERNAME', 'root'),
                'password'  => env('DB_PASSWORD', ''),
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ]
            ]);

            DB::setDefaultConnection('tenant');

            $this->info("Database connection for tenant '$name' set successfully.");

            $this->call('migrate', [
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ]);

            Artisan::call('migrate', [
                '--path' => 'vendor/laravel/passport/database/migrations',
                '--force' => true,
            ]);

            $this->info("Migrations for tenant '$name' executed successfully.");

            if(is_dir(base_path('database/seeders/tenant'))){
                $this->call('db:seed',[
                    '--class' => 'Database\\Seeders\\Tenant\\DatabaseSeeder',
                    '--force' => true,
                ]);
            }
            $this->info("Seeders for tenant '$name' executed successfully.");

            return Command::SUCCESS;
        }
    
    };