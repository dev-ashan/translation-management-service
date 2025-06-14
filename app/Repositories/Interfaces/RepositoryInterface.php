<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
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
     * @return mixed
     */
    public function find(int $id);

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
     * @param int $id
     * @param array $data
     * @return Model|null
     */
    public function update(int $id, array $data): ?Model;

    /**
     * Delete a record
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

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
     * @return mixed
     */
    public function findBy(array $criteria);

    /**
     * Find one record by criteria
     *
     * @param array $criteria
     * @return mixed
     */
    public function findOneBy(array $criteria);

    /**
     * Get paginated records
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;
} 