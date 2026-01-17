<?php

namespace App\Filament\Resources\Grupos\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class IntegrantesVsCuentasBarChart extends ChartWidget
{
    public ?int $grupoId = null;

    public function getHeading(): string
    {
        return 'Integrantes vs Cuentas';
    }

    public static function getEmptyStateHeading(): ?string
    {
        return 'No hay registros de integrantes ni cuentas para este grupo.';
    }

    protected function getData(): array
    {
        if (! $this->grupoId) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $integrantes = DB::table('asignados')
            ->where('id_grupo', $this->grupoId)
            ->where('estado_asignado', 1)
            ->count();

        $cuentas = DB::table('asignados')
            ->join('cuentas', 'cuentas.id_usuario', '=', 'asignados.id_usuario')
            ->where('asignados.id_grupo', $this->grupoId)
            ->where('asignados.estado_asignado', 1)
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
                        '#10b981',
                        '#3b82f6',
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
                ],
            ],
        ];
    }
}
