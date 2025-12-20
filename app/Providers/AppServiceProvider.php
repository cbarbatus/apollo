<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // <-- Don't forget this import!
use Illuminate\Support\Facades\Gate;
use App\Models\User; // Ensure this is the correct path to your User model

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
// THIS IS THE CRITICAL FIX for MySQL Key Length Errors
        Schema::defaultStringLength(191);
        Gate::before(function (User $user, string $ability) {

            // This condition MUST correctly identify your admin user.
            // Since you use Spatie, hasRole('admin') is the safest bet.
            if ($user->hasRole('admin')) {
                return true; // Bypasses ALL other authorization checks (Spatie, Policies, etc.)
            }

            return null;
        });
    }
}
