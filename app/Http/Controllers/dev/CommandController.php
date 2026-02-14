<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;

class CommandController extends Controller
{
    /**
     * Solo permitir en local o usuarios autorizados
     */
    private function authorizeDevAccess()
    {
        if (app()->environment('production')) {
            abort(404);
        }
    }

    /**
     * OPTIMIZAR (NO limpiar)
     */
    public function optimize()
    {
        $this->authorizeDevAccess();

        Artisan::call('optimize');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');

        return redirect()->back()->with('success', 'Sistema optimizado correctamente.');
    }

    /**
     * Limpiar cache SOLO para desarrollo (usar raramente)
     */
    public function clearCache()
    {
        $this->authorizeDevAccess();

        Artisan::call('optimize:clear');

        return redirect()->back()->with('success', 'Cache limpiada.');
    }

    /**
     * Crear symlink storage
     */
    public function createSymlink(): JsonResponse
    {
        $this->authorizeDevAccess();

        if (file_exists(public_path('storage'))) {
            File::delete(public_path('storage'));
        }

        Artisan::call('storage:link');

        return response()->json([
            'message' => 'Symlink creado correctamente.'
        ]);
    }

    public function runMigrate(): JsonResponse
    {
        Artisan::call('migrate', ['--force' => true]);

        return response()->json([
            'success' => true,
            'output' => Artisan::output(),
            'message' => 'Migraci贸n ejecutada correctamente.'
        ]);
    }

    public function runMigrateReset(): \Illuminate\Http\JsonResponse
    {
        Artisan::call('migrate:reset', ['--force' => true]);

        return response()->json([
            'success' => true,
            'output' => Artisan::output(),
            'message' => 'Migraciones revertidas correctamente.'
        ]);
    }
}
