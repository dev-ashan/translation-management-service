<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Repositories\Interfaces\TagRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TagRepository extends BaseRepository implements TagRepositoryInterface
{
    public function __construct(Tag $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all tags with filters
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (isset($filters['search'])) {
            $query->where('name', 'like', "%{$filters['search']}%");
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Create a new tag
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        DB::beginTransaction();
        try {
            $tag = parent::create($data);
            $this->clearTagCache();
            DB::commit();
            return $tag;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update a tag
     *
     * @param mixed $modelOrId
     * @param array $data
     * @return Model|null
     */
    public function update($modelOrId, array $data): ?Model
    {
        DB::beginTransaction();
        try {
            $tag = parent::update($modelOrId, $data);
            if ($tag) {
                $this->clearTagCache();
            }
            DB::commit();
            return $tag;
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    /**
     * Delete a tag
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
                $this->clearTagCache();
            }
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Restore a soft-deleted tag
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
                $this->clearTagCache();
            }
            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    /**
     * Find a tag by name
     *
     * @param string $name
     * @return Tag|null
     */
    public function findByName(string $name): ?Tag
    {
        return Cache::remember("tag_name_{$name}", now()->addHours(24), function () use ($name) {
            return $this->model->where('name', $name)->first();
        });
    }

    /**
     * Clear all tag-related cache
     */
    protected function clearTagCache(): void
    {
        // Clear any tag-specific cache
        $tags = $this->model->all();
        foreach ($tags as $tag) {
            Cache::forget("tag_name_{$tag->name}");
        }
    }
} 