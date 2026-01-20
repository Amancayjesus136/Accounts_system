<?php

namespace App\Filament\Resources\Grupos\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CuentasCreadasPorDiaLineChart extends ChartWidget
{
    public ?int $grupoId = null;

    public function getHeading(): string
    {
        return 'Evolución diaria de cuentas';
    }

    public static function getEmptyStateHeading(): ?string
    {
        return 'No hay actividad registrada en los últimos 30 días para este grupo.';
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

        $rawData = DB::table('users')
            ->joinSub($participantesQuery, 'participantes', function ($join) {
                $join->on('users.id', '=', 'participantes.user_id');
            })
            ->join('cuentas', 'cuentas.id_usuario', '=', 'users.id')
            ->where('cuentas.created_at', '>=', now()->subDays(30))
            ->selectRaw("
                users.name as usuario,
                to_char(cuentas.created_at, 'YYYY-MM-DD') as fecha,
                COUNT(*) as total
            ")
            ->groupBy('usuario', 'fecha')
            ->orderBy('fecha')
            ->get();

        if ($rawData->isEmpty()) {
            return ['datasets' => [], 'labels' => []];
        }

        $labels = $rawData->pluck('fecha')->unique()->sort()->values()->toArray();

        $datasets = [];
        $usuarios = $rawData->groupBy('usuario');
        $colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4'];
        $colorIndex = 0;

        foreach ($usuarios as $nombreUsuario => $registros) {
            $dailyCounts = [];
            $registrosPorDia = $registros->pluck('total', 'fecha');

            foreach ($labels as $fecha) {
                $dailyCounts[] = $registrosPorDia->get($fecha, 0);
            }

            $color = $colors[$colorIndex % count($colors)];

            $datasets[] = [
                'label' => $nombreUsuario,
                'data' => $dailyCounts,
                'borderColor' => $color,
                'backgroundColor' => $color,
                'fill' => false,
                'tension' => 0.4,
                'pointRadius' => 3,
            ];

            $colorIndex++;
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
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
