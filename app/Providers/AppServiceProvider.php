<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\TranslationRepositoryInterface;
use App\Repositories\TranslationRepository;
use App\Services\Interfaces\TranslationServiceInterface;
use App\Services\TranslationService;
use App\Repositories\Interfaces\LocaleRepositoryInterface;
use App\Repositories\LocaleRepository;
use App\Services\Interfaces\LocaleServiceInterface;
use App\Services\LocaleService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        // Repository Bindings
        $this->app->bind(TranslationRepositoryInterface::class, TranslationRepository::class);
        $this->app->bind(LocaleRepositoryInterface::class, LocaleRepository::class);

        // Service Bindings
        $this->app->bind(TranslationServiceInterface::class, TranslationService::class);
        $this->app->bind(LocaleServiceInterface::class, LocaleService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
