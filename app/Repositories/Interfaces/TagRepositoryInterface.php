<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface TagRepositoryInterface
{
    /**
     * Get all tags with pagination and filters
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getAll(array $filters = []): LengthAwarePaginator;

    /**
     * Find a tag by ID
     *
     * @param int $id
     * @return Model|null
     */
    public function find(int $id): ?Model;

    /**
     * Create a new tag
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * Update a tag
     *
     * @param int $id
     * @param array $data
     * @return Model|null
     */
    public function update(int $id, array $data): ?Model;

    /**
     * Delete a tag
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Restore a soft-deleted tag
     *
     * @param int $id
     * @return Model|null
     */
    public function restore(int $id): ?Model;
} 