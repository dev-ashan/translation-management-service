<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements RepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
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

    public function update(int $id, array $data): ?Model
    {
        $record = $this->find($id);
        if ($record) {
            $record->update($data);
            return $record;
        }
        return null;
    }

    public function delete(int $id): bool
    {
        return $this->model->destroy($id);
    }

    public function findBy(array $criteria)
    {
        return $this->model->where($criteria)->get();
    }

    public function findOneBy(array $criteria)
    {
        return $this->model->where($criteria)->first();
    }
} 