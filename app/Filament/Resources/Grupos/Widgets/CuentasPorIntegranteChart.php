<?php

namespace App\Filament\Resources\Grupos\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CuentasPorIntegranteChart extends ChartWidget
{
    public ?int $grupoId = null;

    public function getHeading(): string
    {
        return 'Cuentas por integrante (Incluye Propietario)';
    }

    protected function getData(): array
    {
        if (! $this->grupoId) {
            return ['datasets' => [], 'labels' => []];
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
                'users.name as usuario',
                DB::raw('COUNT(cuentas.id_cuenta) as total')
            )
            ->groupBy('users.name')
            ->orderByDesc('total')
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
                    'label' => 'Total de cuentas',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => $data->pluck('usuario')->toArray(),
        ];
    }

    public static function getEmptyStateHeading(): ?string
    {
        return 'No hay integrantes o cuentas registradas en este grupo.';
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}
