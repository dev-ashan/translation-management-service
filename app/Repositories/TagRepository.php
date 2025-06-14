<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Repositories\Interfaces\TagRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class TagRepository extends BaseRepository implements TagRepositoryInterface
{
    public function __construct(Tag $model)
    {
        parent::__construct($model);
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