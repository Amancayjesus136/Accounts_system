<?php

namespace App\Filament\Widgets;

use App\Models\Gasto;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class GastosDoughnutChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 1;

    protected ?string $heading = 'Distribución de Gastos (%)';

    protected function getData(): array
    {
        $ahora = Carbon::now();

        $data = Gasto::query()
            ->whereMonth('created_at', $ahora->month)
            ->whereYear('created_at', $ahora->year)
            ->where('id_usuario', Auth::id())
            ->with('categoria')
            ->get()
            ->groupBy(fn($gasto) => $gasto->categoria->nombre_categoria ?? 'Sin Categoría')
            ->map(fn($items) => $items->sum('monto'));

        return [
            'datasets' => [
                [
                    'label' => 'Gastos',
                    'data' => $data->values()->toArray(),
                    'backgroundColor' => [
                        '#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#6366f1', '#ec4899', '#8b5cf6'
                    ],
                ],
            ],
            'labels' => $data->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    public static function canView(): bool
    {
        return Auth::id() !== 1;
    }
}
