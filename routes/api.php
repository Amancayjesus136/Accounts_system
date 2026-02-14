<?php

use App\Http\Controllers\dev\CommandController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('dev')->group(function () {
    Route::get('/run-migrate', [CommandController::class, 'runMigrate']);
    Route::get('/run-migrate-reset', [CommandController::class, 'runMigrateReset']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
