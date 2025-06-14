<?php

namespace App\Services\Interfaces;

use App\Models\Locale;
use Illuminate\Pagination\LengthAwarePaginator;

interface LocaleServiceInterface
{
    /**
     * Get all locales with pagination and filters
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getAllLocales(array $filters = []): LengthAwarePaginator;

    /**
     * Get a locale by ID
     *
     * @param int $id
     * @return Locale|null
     */
    public function getLocaleById(int $id): ?Locale;

    /**
     * Create a new locale
     *
     * @param array $data
     * @return Locale
     */
    public function createLocale(array $data): Locale;

    /**
     * Update a locale
     *
     * @param int $id
     * @param array $data
     * @return Locale|null
     */
    public function updateLocale(int $id, array $data): ?Locale;

    /**
     * Delete a locale
     *
     * @param int $id
     * @return bool
     */
    public function deleteLocale(int $id): bool;

    /**
     * Restore a soft-deleted locale
     *
     * @param int $id
     * @return Locale|null
     */
    public function restoreLocale(int $id): ?Locale;

    /**
     * Get all active locales
     *
     * @return array
     */
    public function getActiveLocales(): array;

    public function findByCode(string $code);
} 