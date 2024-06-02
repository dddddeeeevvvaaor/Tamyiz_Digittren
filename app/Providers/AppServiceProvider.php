<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\AkunBaruUser;
use App\Observers\AkunBaruUserObserver;

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
        AkunBaruUser::observe(AkunBaruUserObserver::class);
    }
}
