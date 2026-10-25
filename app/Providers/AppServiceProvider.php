<?php

namespace App\Providers;

use App\Models\User;
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
        // Super Admin Bypass
        Gate::before(function (User $user, string $ability) {
            if ($user->isAdmin()) {
                return true;
            }
        });

        // User Management Gate
        Gate::define('manage-users', function (User $user) {
            return $user->isAdmin();
        });

        // Financials and Accounts Gate
        Gate::define('view-financials', function (User $user) {
            return $user->isAdmin();
        });

        // Purchases & Mill invoices Gate
        Gate::define('manage-purchases', function (User $user) {
            return in_array($user->role, ['admin', 'manager', 'storekeeper']);
        });

        // POS & Sales Counter Gate
        Gate::define('manage-pos', function (User $user) {
            return in_array($user->role, ['admin', 'manager', 'cashier']);
        });

        // Inventory & Warehouse Transfer Gate
        Gate::define('manage-inventory', function (User $user) {
            return in_array($user->role, ['admin', 'manager', 'storekeeper']);
        });

        // Expenses Gate
        Gate::define('manage-expenses', function (User $user) {
            return in_array($user->role, ['admin', 'manager']);
        });
    }
}
