<?php

namespace App\Filament\Resources\Grupos\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DistribucionCuentasBarChart extends ChartWidget
{
    public ?int $grupoId = null;

    public function getHeading(): string
    {
        return 'Distribución de Integrantes por cantidad de cuentas';
    }

    public static function getEmptyStateHeading(): ?string
    {
        return 'No hay datos suficientes para mostrar la distribución.';
    }

    protected function getData(): array
    {
        if (! $this->grupoId) {
            return ['datasets' => [], 'labels' => []];
        }

        /**
         * 1. Primero obtenemos el total de cuentas que tiene cada usuario del grupo
         */
        $countsPerUser = DB::table('asignados')
            ->join('users', 'users.id', '=', 'asignados.id_usuario')
            ->leftJoin('cuentas', 'cuentas.id_usuario', '=', 'users.id')
            ->where('asignados.id_grupo', $this->grupoId)
            ->where('asignados.estado_asignado', 1)
            ->select('users.id', DB::raw('COUNT(cuentas.id_cuenta) as total_cuentas'))
            ->groupBy('users.id')
            ->get();

        if ($countsPerUser->isEmpty()) {
            return ['datasets' => [], 'labels' => []];
        }

        /**
         * 2. Agrupamos cuántos usuarios tienen la misma cantidad de cuentas
         * Ejemplo: 5 usuarios tienen 2 cuentas, 3 usuarios tienen 10 cuentas.
         */
        $distribution = $countsPerUser->groupBy('total_cuentas')
            ->map(fn ($group) => $group->count())
            ->sortKeys(); // Ordenamos por cantidad de cuentas (0, 1, 2, 3...)

        return [
            'datasets' => [
                [
                    'label' => 'Número de Integrantes',
                    'data' => $distribution->values()->toArray(),
                    'backgroundColor' => '#8b5cf6',
                    'borderColor' => '#7c3aed',
                ],
            ],
            // Los labels serán: "0 cuentas", "1 cuenta", "2 cuentas", etc.
            'labels' => $distribution->keys()->map(fn($key) => $key . ($key == 1 ? ' cuenta' : ' cuentas'))->toArray(),
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
                    'title' => [
                        'display' => true,
                        'text' => 'Cantidad de Integrantes',
                    ],
                    'ticks' => ['stepSize' => 1],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Rango de Cuentas creadas',
                    ],
                ],
            ],
        ];
    }
}
