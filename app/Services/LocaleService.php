<?php

namespace App\Services;

use App\Models\Locale;
use App\Repositories\Interfaces\LocaleRepositoryInterface;
use App\Services\Interfaces\LocaleServiceInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

class LocaleService implements LocaleServiceInterface
{
    protected $localeRepository;

    public function __construct(LocaleRepositoryInterface $localeRepository)
    {
        $this->localeRepository = $localeRepository;
    }

    public function getAllLocales(array $filters = []): LengthAwarePaginator
    {
        return $this->localeRepository->getAll($filters);
    }

    public function getLocaleById(int $id): ?Locale
    {
        return $this->localeRepository->find($id);
    }

    public function createLocale(array $data): Locale
    {
        return $this->localeRepository->create($data);
    }

    public function updateLocale(int $id, array $data): ?Locale
    {
        return $this->localeRepository->update($id, $data);
    }

    public function deleteLocale(int $id): bool
    {
        $locale = $this->getLocaleById($id);
        
        if ($locale->is_default) {
            throw new \Exception('Cannot delete default locale');
        }

        if ($locale->is_active) {
            throw new \Exception('Cannot delete active locale');
        }

        return $this->localeRepository->delete($id);
    }

    public function restoreLocale(int $id): ?Locale
    {
        return $this->localeRepository->restore($id);
    }

    public function getActiveLocales(): array
    {
        return $this->localeRepository->getActiveLocales();
    }

    public function findByCode(string $code)
    {
        return $this->localeRepository->findByCode($code);
    }
} 