<?php

namespace App\Filament\Resources\Grupos\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CuentasPorIntegrantePieChart extends ChartWidget
{
    public ?int $grupoId = null;

    public function getHeading(): string
    {
        return 'Distribución de cuentas';
    }

    public static function getEmptyStateHeading(): ?string
    {
        return 'No hay datos de distribución para los miembros de este grupo.';
    }

    protected function getData(): array
    {
        if (! $this->grupoId) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $participantesQuery = DB::table('grupos')
            ->where('id_grupo', $this->grupoId)
            ->select('id_user as user_id')
            ->union(
                DB::table('asignados')
                    ->where('id_grupo', $this->grupoId)
                    ->where('estado_asignado', 1)
                    ->select('id_usuario as user_id')
            );

        $data = DB::table('users')
            ->joinSub($participantesQuery, 'participantes', function ($join) {
                $join->on('users.id', '=', 'participantes.user_id');
            })
            ->leftJoin('cuentas', 'cuentas.id_usuario', '=', 'users.id')
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
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6',
                        '#ec4899',
                        '#06b6d4',
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

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
