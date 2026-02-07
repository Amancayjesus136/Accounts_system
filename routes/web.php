<?php

use App\Http\Controllers\dev\CommandController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {

    if (Auth::check()) {
        return redirect('/admin');
    }

    return redirect('/admin/login');

});

    Route::get('/speed-test', function () {
        return response()->json(['ok' => true]);
    });

    Route::get('/clear-cache', [CommandController::class, 'clearCache'])->name('clear.cache');
    Route::get('/create-symlink', [CommandController::class, 'createSymlink']);
    Route::get('/ejecutar-npm-dev', [CommandController::class, 'runNpmDev']);
    Route::get('/ejecutar-npm-build', [CommandController::class, 'runNpmDev']);
    Route::get('/ejecutar-npm-install', [CommandController::class, 'runNpmInstall']);
    Route::get('/ejecutar-install-vite', [CommandController::class, 'installVite']);
