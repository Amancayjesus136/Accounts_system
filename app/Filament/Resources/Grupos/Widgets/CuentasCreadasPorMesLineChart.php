<?php

namespace App\Filament\Resources\Grupos\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CuentasCreadasPorMesLineChart extends ChartWidget
{
    public ?int $grupoId = null;

    public function getHeading(): string
    {
        return 'Evolución mensual de cuentas';
    }

    public static function getEmptyStateHeading(): ?string
    {
        return 'No hay historial de cuentas creadas para los miembros de este grupo.';
    }

    protected function getData(): array
    {
        if (! $this->grupoId) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        /**
         * 1. Unificamos al dueño del grupo y a los integrantes asignados
         */
        $participantesQuery = DB::table('grupos')
            ->where('id_grupo', $this->grupoId)
            ->select('id_user as user_id') // Propietario
            ->union(
                DB::table('asignados')
                    ->where('id_grupo', $this->grupoId)
                    ->where('estado_asignado', 1)
                    ->select('id_usuario as user_id') // Integrantes
            );

        /**
         * 2. Obtenemos el conteo agrupado por mes (YYYY-MM)
         * Cruzamos la tabla de cuentas con nuestra lista unificada de participantes
         */
        $data = DB::table('cuentas')
            ->joinSub($participantesQuery, 'participantes', function ($join) {
                $join->on('cuentas.id_usuario', '=', 'participantes.user_id');
            })
            ->selectRaw("to_char(cuentas.created_at, 'YYYY-MM') as mes, COUNT(*) as total")
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        if ($data->isEmpty()) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Cuentas creadas (Total Grupo)',
                    'data' => $data->pluck('total')->toArray(),
                    'fill' => 'start',
                    'tension' => 0.4,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
            ],
            'labels' => $data->pluck('mes')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
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
