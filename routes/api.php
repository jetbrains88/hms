<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\SearchController;

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    // User info
    Route::get('/user/branches', [UserController::class, 'branches'])->name('api.user.branches');
    Route::get('/user/permissions', [UserController::class, 'permissions'])->name('api.user.permissions');
    Route::get('/user/context', [UserController::class, 'context'])->name('api.user.context');

    // Global search
    Route::get('/search', [SearchController::class, 'global'])->name('api.search');
    Route::get('/search/patients', [SearchController::class, 'patients'])->name('api.search.patients');
    Route::get('/search/medicines', [SearchController::class, 'medicines'])->name('api.search.medicines');
});
