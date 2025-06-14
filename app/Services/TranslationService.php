<?php

namespace App\Services;

use App\Models\Translation;
use App\Repositories\Interfaces\TranslationRepositoryInterface;
use App\Services\Interfaces\TranslationServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class TranslationService implements TranslationServiceInterface
{
    public function __construct(
        protected TranslationRepositoryInterface $translationRepository
    ) {}

    /**
     * Get all translations with optional filters
     */
    public function getAllTranslations(array $filters = []): LengthAwarePaginator
    {
        return $this->translationRepository->getAll($filters);
    }

    /**
     * Get a translation by ID
     */
    public function getTranslationById(int $id): ?Translation
    {
        return $this->translationRepository->findById($id);
    }

    /**
     * Create a new translation
     */
    public function createTranslation(array $data): Translation
    {
        $translation = $this->translationRepository->create($data);
        $this->clearTranslationCache($translation->locale_id);
        return $translation->load(['locale', 'tags']);
    }

    /**
     * Update a translation
     */
    public function update(int $id, array $data): ?Translation
    {
        $translation = $this->translationRepository->findById($id);
        
        if (!$translation) {
            return null;
        }

        $updatedTranslation = $this->translationRepository->update($translation, $data);
        
        if ($updatedTranslation) {
            $this->clearTranslationCache($updatedTranslation->locale_id);
            return $updatedTranslation;
        }

        return null;
    }

    /**
     * Delete a translation
     */
    public function deleteTranslation(int $id): bool
    {
        $translation = $this->translationRepository->findById($id);
        
        if (!$translation) {
            return false;
        }

        $deleted = $this->translationRepository->delete($translation);
        
        if ($deleted) {
            $this->clearTranslationCache($translation->locale_id);
        }

        return $deleted;
    }

    /**
     * Search translations
     */
    public function searchTranslations(string $query): LengthAwarePaginator
    {
        return $this->getAllTranslations(['query' => $query]);
    }

    /**
     * Get translations by locale ID
     */
    public function getTranslationsByLocaleId(int $localeId): Collection
    {
        return $this->translationRepository->findByLocaleId($localeId);
    }

    /**
     * Get translations by key
     */
    public function getTranslationsByKey(string $key): Collection
    {
        return $this->translationRepository->findByKey($key);
    }

    /**
     * Clear translation cache for a specific locale
     */
    protected function clearTranslationCache(int $localeId): void
    {
        Cache::forget("translations_locale_{$localeId}");
        Cache::forget('translations_export_all');
    }
} 