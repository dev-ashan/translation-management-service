<?php

namespace App\Services\Interfaces;

use App\Models\Tag;
use Illuminate\Pagination\LengthAwarePaginator;

interface TagServiceInterface
{
    public function getAllTags(array $filters = []): LengthAwarePaginator;
    public function getTagById(int $id): ?Tag;
    public function getTagByName(string $name): ?Tag;
    public function createTag(array $data): Tag;
    public function updateTag(int $id, array $data): ?Tag;
    public function deleteTag(int $id): bool;
    public function restoreTag(int $id): ?Tag;
} 