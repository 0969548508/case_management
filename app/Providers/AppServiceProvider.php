<?php

namespace App\Providers;

use Stancl\Tenancy\TenantManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        tenancy()->hook('bootstrapped', function (TenantManager $tenantManager) {
            \Spatie\Permission\PermissionRegistrar::$cacheKey = 'spatie.permission.cache.tenant.' . $tenantManager->getTenant('id');
        });

        Schema::defaultStringLength(191);
    }
}
