<?php

namespace App\Repositories\Interfaces;

use App\Models\Translation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface TranslationRepositoryInterface
{
    /**
     * Get all translations with optional filters
     */
    public function getAll(array $filters = []): LengthAwarePaginator;

    /**
     * Find a translation by ID
     */
    public function findById(int $id): ?Translation;

    /**
     * Create a new translation
     */
    public function create(array $data): Translation;

    /**
     * Update an existing translation
     *
     * @param Translation $translation
     * @param array $data
     * @return Translation|null
     */
    public function update(Translation $translation, array $data): ?Translation;

    /**
     * Delete a translation
     */
    public function delete(Translation $translation): bool;

    /**
     * Find translations by locale ID
     */
    public function findByLocaleId(int $localeId): Collection;

    /**
     * Find translations by key
     */
    public function findByKey(string $key): Collection;
} 