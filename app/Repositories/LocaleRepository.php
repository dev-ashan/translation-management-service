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

    public function update(int $id, array $data): ?Model
    {
        $locale = parent::update($id, $data);
        Cache::forget('active_locales');
        return $locale;
    }

    public function delete(int $id): bool
    {
        $result = parent::delete($id);
        Cache::forget('active_locales');
        return $result;
    }

    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return $this->model->paginate();
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