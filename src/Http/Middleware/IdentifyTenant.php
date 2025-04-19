<?php

namespace MultiTenant\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use MultiTenant\Models\Tenant;
use Symphony\Component\HttpKernel\Exception\NotFoundHttpException;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        $subdomain = $this->getSubdomain($host);

        if(!$subdomain || in_array($subdomain, config('multi-tenant.reserved_subdomains', []))){
            throw new NotFoundHttpException('Subdomain not found or reserved.');
        }

        $tenant = Tenant::where('subdomain', $subdomain)->first();

        if(!$tenant){
            throw new NotFoundHttpException('Tenant not found.');
        }

        if($tenant->status === false){
            abort(403, 'Tenant desativado.');
        }

        $this->configureTenantDatabase($tenant);

        app()->instance('tenant', $tenant);
        return $next($request);
    }

    protected function getSubdomain(string $host) : ?string
    {
        $connectionName = 'tenant';

        config([
            "database.connections.$connectionName" => [
                'driver' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => $tenant->database,
                'username' => env('DB_USERNAME', 'root'),
                'password' => env('DB_PASSWORD', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ]
            ]);

            DB::setDefaultConnection($connectionName);
       
    }
   
}