<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface TranslationRepositoryInterface extends RepositoryInterface
{
    /**
     * Find a translation by ID
     */
    public function findById(int $id);

    /**
     * Find translations by locale ID
     */
    public function findByLocaleId(int $localeId): Collection;

    /**
     * Find translations by key
     */
    public function findByKey(string $key): Collection;
} 