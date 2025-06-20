<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Gate; // ✅ tambahkan ini
use App\Models\ClusterResult;
use App\Policies\ClusterResultPolicy;

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
        //
        Gate::policy(ClusterResult::class, ClusterResultPolicy::class);
    }
}
