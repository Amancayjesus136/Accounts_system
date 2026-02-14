<?php

namespace App\Filament\Widgets;

use App\Models\Gasto;
use App\Models\Ingreso;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $ahora = Carbon::now();
        $nombreMes = $ahora->translatedFormat('F');
        $userId = Auth::id();

        $totalGastosMes = Gasto::query()
            ->whereMonth('created_at', $ahora->month)
            ->whereYear('created_at', $ahora->year)
            ->where('id_usuario', $userId)
            ->sum('monto');

        $totalIngresosMes = Ingreso::query()
            ->whereMonth('created_at', $ahora->month)
            ->whereYear('created_at', $ahora->year)
            ->where('id_usuario', $userId)
            ->sum('monto');

        return [
            Stat::make("Ingresos de " . ucfirst($nombreMes), "S/ " . number_format($totalIngresosMes, 2))
                ->description('Dinero recibido este mes')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([3, 7, 5, 12, 9, 15, 20]),

            Stat::make("Gastos de " . ucfirst($nombreMes), "S/ " . number_format($totalGastosMes, 2))
                ->description('Dinero gastado este mes')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger')
                ->chart([5, 10, 8, 15, 12, 20, 18]),

            Stat::make('Operaciones', Gasto::where('id_usuario', $userId)->whereMonth('created_at', $ahora->month)->count())
                ->description('Total de transacciones realizadas')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('primary'),
        ];
    }
}
