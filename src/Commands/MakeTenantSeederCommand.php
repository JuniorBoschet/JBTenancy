<?php

namespace MultiTenant\Commands;

use Illuminate\Database\Console\Seeds\SeederMakeCommand;
use Symfony\Component\Console\Input\InputOption;


class MakeTenantSeederCommand extends SeederMakeCommand
{
    protected $name = 'make:tenant-seeder';

    protected $description = 'Create a new tenant seeder class';

    protected function getPath($name)
    {
        return database_path('seeders/tenant') . '/' .  $name . '.php';
    }

    protected function getOptions()
    {
        return array_merge(parent::getOptions(), [
            ['path', null, InputOption::VALUE_OPTIONAL, 'Costum path for the seeder class'],
        ]);
    }
}