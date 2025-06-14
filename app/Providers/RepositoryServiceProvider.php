<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Repositories\Interfaces\TranslationRepositoryInterface;
use App\Repositories\Interfaces\LocaleRepositoryInterface;
use App\Repositories\TagRepository;
use App\Repositories\TranslationRepository;
use App\Repositories\LocaleRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);
        $this->app->bind(TranslationRepositoryInterface::class, TranslationRepository::class);
        $this->app->bind(LocaleRepositoryInterface::class, LocaleRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
} 