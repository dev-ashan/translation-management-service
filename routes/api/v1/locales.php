<?php

use App\Http\Controllers\Api\LocaleController;
use Illuminate\Support\Facades\Route;

Route::apiResource('locales', LocaleController::class);
Route::post('locales/{id}/restore', [LocaleController::class, 'restore']); 