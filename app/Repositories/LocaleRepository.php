<?php

namespace App\Repositories;

use App\Models\Locale;
use App\Repositories\Interfaces\LocaleRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class LocaleRepository extends BaseRepository implements LocaleRepositoryInterface
{
    public function __construct(Locale $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all locales with filters
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                    ->orWhere('code', 'like', "%{$filters['search']}%");
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Find a locale by its code
     *
     * @param string $code
     * @return Locale|null
     */
    public function findByCode(string $code): ?Locale
    {
        return Cache::remember("locale_code_{$code}", now()->addHours(24), function () use ($code) {
            return $this->model->where('code', $code)->first();
        });
    }

    /**
     * Get all active locales
     *
     * @return array
     */
    public function getActiveLocales(): array
    {
        return Cache::remember('active_locales', now()->addHours(24), function () {
            return $this->model->where('is_active', true)->get()->toArray();
        });
    }

    /**
     * Create a new locale
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        DB::beginTransaction();
        try {
            $locale = parent::create($data);
            $this->clearLocaleCache();
            DB::commit();
            return $locale;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update a locale
     *
     * @param mixed $modelOrId
     * @param array $data
     * @return Model|null
     */
    public function update($modelOrId, array $data): ?Model
    {
        DB::beginTransaction();
        try {
            $locale = parent::update($modelOrId, $data);
            if ($locale) {
                $this->clearLocaleCache();
            }
            DB::commit();
            return $locale;
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    /**
     * Delete a locale
     *
     * @param mixed $modelOrId
     * @return bool
     */
    public function delete($modelOrId): bool
    {
        DB::beginTransaction();
        try {
            $result = parent::delete($modelOrId);
            if ($result) {
                $this->clearLocaleCache();
            }
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Restore a soft-deleted locale
     *
     * @param int $id
     * @return Model|null
     */
    public function restore(int $id): ?Model
    {
        DB::beginTransaction();
        try {
            $model = $this->model->withTrashed()->find($id);
            if ($model) {
                $model->restore();
                $this->clearLocaleCache();
            }
            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    /**
     * Clear all locale-related cache
     */
    protected function clearLocaleCache(): void
    {
        Cache::forget('active_locales');
        // Clear any locale-specific cache
        $locales = $this->model->all();
        foreach ($locales as $locale) {
            Cache::forget("locale_code_{$locale->code}");
        }
    }
} 