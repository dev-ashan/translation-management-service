<?php

use App\Http\Controllers\Api\TagController;
use Illuminate\Support\Facades\Route;

Route::apiResource('tags', TagController::class);
Route::post('tags/{id}/restore', [TagController::class, 'restore']); 