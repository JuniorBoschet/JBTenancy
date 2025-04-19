<?php

namespace MultiTenant\Commands;

use Illuminate\Database\Console\Migrations\MigrateMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class MakeTenantMigrationCommand extends MigrateMakeCommand
{
    protected $name = 'make:tenant-migration';

    protected $description = 'Create a new migration file for tenant database.';

    protected function getMigrationPath()
    {
        return database_path('migrations/tenant');
    }

    protected function getOptions()
    {
        return array_merge(parent::getOptions(), [
            ['path', null, InputOption::VALUE_OPTIONAL, 'The path where the migration file should be created.'],
        ]);
    }
}