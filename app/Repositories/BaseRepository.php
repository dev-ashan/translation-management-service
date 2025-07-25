<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseRepository implements RepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return $this->model->paginate();
    }

    /**
     * Find a record by ID
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update($modelOrId, array $data): ?Model
    {
        $record = $modelOrId instanceof Model ? $modelOrId : $this->find($modelOrId);
        if ($record) {
            $record->update($data);
            return $record;
        }
        return null;
    }

    public function delete($modelOrId): bool
    {
        $record = $modelOrId instanceof Model ? $modelOrId : $this->find($modelOrId);
        if ($record) {
            return $record->delete();
        }
        return false;
    }

    public function findBy(array $criteria): Collection
    {
        return $this->model->where($criteria)->get();
    }

    public function findOneBy(array $criteria): ?Model
    {
        return $this->model->where($criteria)->first();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }
} 