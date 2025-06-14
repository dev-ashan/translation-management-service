<?php

namespace App\Repositories;

use App\Models\Translation;
use App\Repositories\Interfaces\TranslationRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Repository class for managing translation data access
 * 
 * This class handles all database operations related to translations,
 * including CRUD operations, filtering, and relationship management.
 */
class TranslationRepository implements TranslationRepositoryInterface
{
    /**
     * Create a new translation repository instance.
     *
     * @param Translation $model The translation model instance
     */
    public function __construct(
        protected Translation $model
    ) {}

    /**
     * Get all translations with filters
     *
     * @param array $filters Optional filters to apply to the query
     *                      - locale: Filter by locale code or name
     *                      - tags: Filter by tag names
     *                      - query: Search in key and value
     * @return LengthAwarePaginator
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->with(['locale', 'tags']);

        return $this->applyFilters($query, $filters)
            ->orderBy('created_at', 'desc')
            ->paginate();
    }

    /**
     * Apply all filters to the query
     *
     * @param Builder $query The query builder instance
     * @param array $filters The filters to apply
     * @return Builder
     */
    protected function applyFilters(Builder $query, array $filters): Builder
    {
        if (isset($filters['locale'])) {
            $this->applyLocaleFilter($query, $filters['locale']);
        }
        
        if (isset($filters['tags'])) {
            $this->applyTagFilter($query, $filters['tags']);
        }
        
        if (isset($filters['query'])) {
            $this->applySearchFilter($query, $filters['query']);
        }

        return $query;
    }

    /**
     * Apply locale filter by code or name
     *
     * @param Builder $query The query builder instance
     * @param string $searchTerm The search term to match against locale code or name
     * @return void
     */
    protected function applyLocaleFilter(Builder $query, string $searchTerm): void
    {
        $query->whereHas('locale', function (Builder $q) use ($searchTerm) {
            $q->where('code', 'like', "%{$searchTerm}%")
              ->orWhere('name', 'like', "%{$searchTerm}%");
        });
    }

    /**
     * Apply tag filter by name
     *
     * @param Builder $query The query builder instance
     * @param string|array $tags The tag names to filter by
     * @return void
     */
    protected function applyTagFilter(Builder $query, string|array $tags): void
    {
        $tagNames = is_array($tags) ? $tags : explode(',', $tags);
        $query->whereHas('tags', function (Builder $q) use ($tagNames) {
            $q->where(function (Builder $subQuery) use ($tagNames) {
                foreach ($tagNames as $name) {
                    $subQuery->orWhere('name', 'like', "%{$name}%");
                }
            });
        });
    }

    /**
     * Apply search filter to key and value
     *
     * @param Builder $query The query builder instance
     * @param string $searchTerm The search term to match against key and value
     * @return void
     */
    protected function applySearchFilter(Builder $query, string $searchTerm): void
    {
        $query->where(function (Builder $q) use ($searchTerm) {
            $q->where('key', 'like', "%{$searchTerm}%")
              ->orWhere('value', 'like', "%{$searchTerm}%");
        });
    }

    /**
     * Create a new translation
     *
     * @param array $data The translation data
     *                    - locale_id: The ID of the locale
     *                    - key: The translation key
     *                    - value: The translation value
     *                    - tags: Optional array of tag IDs
     * @return Translation The created translation
     * @throws \Exception If the creation fails
     */
    public function create(array $data): Translation
    {
        DB::beginTransaction();
        try {
            $translation = $this->model->create($data);
            
            if (isset($data['tags'])) {
                $translation->tags()->sync($data['tags']);
            }
            
            DB::commit();
            Cache::forget('translations_export_all');
            return $translation;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update an existing translation
     *
     * @param Translation $translation The translation to update
     * @param array $data The updated translation data
     *                    - locale_id: The ID of the locale
     *                    - key: The translation key
     *                    - value: The translation value
     *                    - tags: Optional array of tag IDs
     * @return Translation|null The updated translation model or null on failure
     * @throws \Exception If the update fails
     */
    public function update(Translation $translation, array $data): ?Translation
    {
        DB::beginTransaction();
        try {
            $translation->update($data);
            if (isset($data['tags'])) {
                $translation->tags()->sync($data['tags']);
            }
            DB::commit();
            Cache::forget('translations_export_all');
            return $translation->fresh(['locale', 'tags']);
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    /**
     * Delete a translation
     *
     * @param Translation $translation The translation to delete
     * @return bool True if the deletion was successful
     */
    public function delete(Translation $translation): bool
    {
        return $translation->delete();
    }

    /**
     * Find a translation by ID
     *
     * @param int $id The ID of the translation to find
     * @return Translation|null The translation if found, null otherwise
     */
    public function findById(int $id): ?Translation
    {
        return $this->model->with(['locale', 'tags'])->find($id);
    }

    /**
     * Find translations by locale ID
     *
     * @param int $localeId The ID of the locale
     * @return Collection The collection of translations for the locale
     */
    public function findByLocaleId(int $localeId): Collection
    {
        return $this->model->where('locale_id', $localeId)->get();
    }

    /**
     * Find translations by key
     *
     * @param string $key The translation key to search for
     * @return Collection The collection of translations with the given key
     */
    public function findByKey(string $key): Collection
    {
        return $this->model->where('key', $key)->get();
    }
} 