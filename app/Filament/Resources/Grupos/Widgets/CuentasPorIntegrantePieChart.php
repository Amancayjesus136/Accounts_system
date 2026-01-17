<?php

namespace App\Filament\Resources\Grupos\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CuentasPorIntegrantePieChart extends ChartWidget
{
    public ?int $grupoId = null;

    public function getHeading(): string
    {
        return 'Distribución de cuentas por integrante';
    }

    public static function getEmptyStateHeading(): ?string
    {
        return 'No hay datos de distribución para este grupo.';
    }

    protected function getData(): array
    {
        if (! $this->grupoId) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $data = DB::table('asignados')
            ->join('users', 'users.id', '=', 'asignados.id_usuario')
            ->leftJoin('cuentas', 'cuentas.id_usuario', '=', 'users.id')
            ->where('asignados.id_grupo', $this->grupoId)
            ->where('asignados.estado_asignado', 1)
            ->select(
                'users.name',
                DB::raw('COUNT(cuentas.id_cuenta) as total')
            )
            ->groupBy('users.name')
            ->get();

        if ($data->isEmpty() || $data->sum('total') == 0) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Cuentas',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'
                    ],
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
