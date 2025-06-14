<?php

namespace App\Services\Interfaces;

use App\Models\Translation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface TranslationServiceInterface
{
    /**
     * Get all translations with optional filters
     */
    public function getAllTranslations(array $filters = []): LengthAwarePaginator;

    /**
     * Get a translation by ID
     */
    public function getTranslationById(int $id): ?Translation;

    /**
     * Create a new translation
     */
    public function createTranslation(array $data): Translation;

    /**
     * Update a translation
     */
    public function update(int $id, array $data): ?Translation;

    /**
     * Delete a translation
     */
    public function deleteTranslation(int $id): bool;

    /**
     * Search translations
     */
    public function searchTranslations(string $query): LengthAwarePaginator;

    /**
     * Get translations by locale ID
     */
    public function getTranslationsByLocaleId(int $localeId): Collection;

    /**
     * Get translations by key
     */
    public function getTranslationsByKey(string $key): Collection;
} 