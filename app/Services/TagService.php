<?php

namespace App\Services;

use App\Models\Tag;
use App\Repositories\TagRepository;
use App\Services\Interfaces\TagServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class TagService implements TagServiceInterface
{
    protected TagRepository $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function getAllTags(array $filters = []): LengthAwarePaginator
    {
        return $this->tagRepository->getAll($filters);
    }

    public function getTagById(int $id): ?Tag
    {
        return $this->tagRepository->find($id);
    }

    public function createTag(array $data): Tag
    {
        return $this->tagRepository->create($data);
    }

    public function updateTag(int $id, array $data): ?Tag
    {
        return $this->tagRepository->update($id, $data);
    }

    public function deleteTag(int $id): bool
    {
        return $this->tagRepository->delete($id);
    }

    public function restoreTag(int $id): ?Tag
    {
        return $this->tagRepository->restore($id);
    }
} 