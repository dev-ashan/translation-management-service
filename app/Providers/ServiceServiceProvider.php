<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Interfaces\TagServiceInterface;
use App\Services\Interfaces\TranslationServiceInterface;
use App\Services\Interfaces\LocaleServiceInterface;
use App\Services\TagService;
use App\Services\TranslationService;
use App\Services\LocaleService;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TagServiceInterface::class, TagService::class);
        $this->app->bind(TranslationServiceInterface::class, TranslationService::class);
        $this->app->bind(LocaleServiceInterface::class, LocaleService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
} 