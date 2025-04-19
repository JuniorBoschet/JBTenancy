<?php

namespace MultiTenant\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'subdomain',
        'database',
        'status,',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public $timestamps = true;
}