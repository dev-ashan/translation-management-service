<?php

namespace App\Repositories\Interfaces;

interface LocaleRepositoryInterface extends RepositoryInterface
{
    public function findByCode(string $code);
    public function getActiveLocales();
} 