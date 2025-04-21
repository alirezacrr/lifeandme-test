<?php

namespace App\Providers;

use App\Interfaces\BirthdayCeremonyServiceInterface;
use App\Interfaces\BirthdayNotificationServiceInterface;
use App\Services\BirthdayCeremonyService;
use App\Services\BirthdayNotificationService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(BirthdayNotificationServiceInterface::class, BirthdayNotificationService::class);
        $this->app->singleton(BirthdayCeremonyServiceInterface::class, BirthdayCeremonyService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
