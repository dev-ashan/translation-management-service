<?php

use App\Http\Controllers\Api\TranslationController;
use Illuminate\Support\Facades\Route;

// Translation routes with filtering support

Route::get('translations/search', [TranslationController::class, 'search']);
Route::post('translations/{id}/restore', [TranslationController::class, 'restore']);
Route::post('translations/clear-cache', [TranslationController::class, 'clearCache']); 
Route::apiResource('translations', TranslationController::class);