<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface RepositoryInterface
{
    /**
     * Get all records
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Get all records with pagination and filters
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getAll(array $filters = []): LengthAwarePaginator;

    /**
     * Find a record by ID
     *
     * @param int $id
     * @return Model|null
     */
    public function find(int $id): ?Model;

    /**
     * Create a new record
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * Update a record
     *
     * @param mixed $modelOrId
     * @param array $data
     * @return Model|null
     */
    public function update($modelOrId, array $data): ?Model;

    /**
     * Delete a record
     *
     * @param mixed $modelOrId
     * @return bool
     */
    public function delete($modelOrId): bool;

    /**
     * Restore a soft-deleted record
     *
     * @param int $id
     * @return Model|null
     */
    public function restore(int $id): ?Model;

    /**
     * Find records by criteria
     *
     * @param array $criteria
     * @return Collection
     */
    public function findBy(array $criteria): Collection;

    /**
     * Find one record by criteria
     *
     * @param array $criteria
     * @return Model|null
     */
    public function findOneBy(array $criteria): ?Model;

    /**
     * Get paginated records
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;
} 