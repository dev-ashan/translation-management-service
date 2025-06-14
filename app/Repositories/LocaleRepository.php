<?php

namespace App\Repositories;

use App\Models\Locale;
use App\Repositories\Interfaces\LocaleRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class LocaleRepository extends BaseRepository implements LocaleRepositoryInterface
{
    public function __construct(Locale $model)
    {
        parent::__construct($model);
    }

    public function findByCode(string $code)
    {
        return $this->model->where('code', $code)->first();
    }

    public function getActiveLocales()
    {
        return Cache::remember('active_locales', now()->addHours(24), function () {
            return $this->model->where('is_active', true)->get();
        });
    }

    public function create(array $data): Model
    {
        $locale = parent::create($data);
        Cache::forget('active_locales');
        return $locale;
    }

    public function update($modelOrId, array $data): ?Model
    {
        $locale = parent::update($modelOrId, $data);
        Cache::forget('active_locales');
        return $locale;
    }

    public function delete($modelOrId): bool
    {
        $result = parent::delete($modelOrId);
        Cache::forget('active_locales');
        return $result;
    }

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

    public function restore(int $id): ?Model
    {
        $model = $this->model->withTrashed()->find($id);
        if ($model) {
            $model->restore();
            return $model;
        }
        return null;
    }
} 