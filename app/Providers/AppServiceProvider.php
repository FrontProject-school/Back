<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Admin;
use App\Models\User;

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
        Gate::define('admin-or-general-admin', function (Admin $admin) {
            return $admin->position === 'admin' || $admin->position === 'general_admin';
        });

        Gate::define('only-general-admin', function (Admin $admin) {
            return $admin->position === 'general_admin';
        });
    }
}
