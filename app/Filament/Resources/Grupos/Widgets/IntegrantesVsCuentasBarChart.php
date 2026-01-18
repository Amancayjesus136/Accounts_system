<?php

namespace App\Filament\Resources\Grupos\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class IntegrantesVsCuentasBarChart extends ChartWidget
{
    public ?int $grupoId = null;

    public function getHeading(): string
    {
        return 'Integrantes vs Cuentas (Incluye Propietario)';
    }

    public static function getEmptyStateHeading(): ?string
    {
        return 'No hay registros de integrantes ni cuentas para este grupo.';
    }

    protected function getData(): array
    {
        if (! $this->grupoId) {
            return ['datasets' => [], 'labels' => []];
        }

        /**
         * 1. Definimos la lista única de IDs de participantes (Dueño + Asignados)
         */
        $participantesQuery = DB::table('grupos')
            ->where('id_grupo', $this->grupoId)
            ->select('id_user as user_id')
            ->union(
                DB::table('asignados')
                    ->where('id_grupo', $this->grupoId)
                    ->where('estado_asignado', 1)
                    ->select('id_usuario as user_id')
            );

        /**
         * 2. Contamos el total de integrantes únicos
         */
        $integrantes = DB::query()
            ->fromSub($participantesQuery, 'p')
            ->count();

        /**
         * 3. Contamos el total de cuentas de esos integrantes específicos
         */
        $cuentas = DB::table('cuentas')
            ->joinSub($participantesQuery, 'p', function ($join) {
                $join->on('cuentas.id_usuario', '=', 'p.user_id');
            })
            ->count();

        if ($integrantes === 0 && $cuentas === 0) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Cantidad Total',
                    'data' => [$integrantes, $cuentas],
                    'backgroundColor' => [
                        '#10b981', // Verde para integrantes
                        '#3b82f6', // Azul para cuentas
                    ],
                ],
            ],
            'labels' => ['Integrantes', 'Cuentas'],
        ];
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
                        'precision' => 0,
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Total acumulado',
                    ],
                ],
            ],
        ];
    }
}
