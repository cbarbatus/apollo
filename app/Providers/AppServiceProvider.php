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
        Schema::defaultStringLength(191);

        // Explicitly register the policy for Laravel 12
        Gate::policy(\App\Models\Member::class, \App\Policies\MemberPolicy::class);

        Gate::before(function (User $user, string $ability) {
            if ($user->hasRole('admin')) {
                return true;
            }
            return null;
        });
    }
}
