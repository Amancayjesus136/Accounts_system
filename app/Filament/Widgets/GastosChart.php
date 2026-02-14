<?php

namespace App\Filament\Widgets;

use App\Models\Gasto;
use App\Models\Ingreso;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class GastosChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    protected ?string $heading = 'Ingresos por Categoría';

    protected function getData(): array
    {
        $ahora = Carbon::now();

        $data = Ingreso::query()
            ->whereMonth('created_at', $ahora->month)
            ->whereYear('created_at', $ahora->year)
            ->where('id_usuario', Auth::id())
            ->with('categoria')
            ->get()
            ->groupBy(fn($ingreso) => $ingreso->categoria->nombre_categoria ?? 'Sin Categoría')
            ->map(fn($items) => $items->sum('monto'));

        return [
            'datasets' => [
                [
                    'label' => 'Monto en Soles',
                    'data' => $data->values()->toArray(),
                    'backgroundColor' => [
                        '#10b981',
                        '#3b82f6',
                        '#8b5cf6',
                        '#06b6d4',
                        '#22c55e',
                    ],
                ],
            ],
            'labels' => $data->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    public static function canView(): bool
    {
        return Auth::id() !== 1;
    }
}
