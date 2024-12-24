<?php

namespace App\Providers;


use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Gate::define('manage-users', function ($user) {
            return $user->hasPermission(['user-admin', 'user-management']);
        });
    
        Gate::define('access-data', function ($user) {
            return $user->hasPermission(['import-orders', 'import-products', 'import-customers']);
        });
    
        Gate::define('restrict-users-route', function ($user) {
            return !$user->hasPermission(['import-orders', 'import-products', 'import-customers']) && !$user->permissions || empty($user->permissions);
        });

        Gate::define('import-orders', function ($user) {
            return $user->hasPermission('import-orders');
        });
        Gate::define('import-products', function ($user) {
            return $user->hasPermission('import-products');
        });
        Gate::define('import-customers', function ($user) {
            return $user->hasPermission('import-customers');
        });
    }
}
