<?php

namespace App\Services;

use App\Models\Locale;
use App\Repositories\LocaleRepository;
use App\Services\Interfaces\LocaleServiceInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

class LocaleService implements LocaleServiceInterface
{
    protected LocaleRepository $localeRepository;

    public function __construct(LocaleRepository $localeRepository)
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

    public function findByCode(string $code): ?Locale
    {
        return $this->localeRepository->findByCode($code);
    }
} 