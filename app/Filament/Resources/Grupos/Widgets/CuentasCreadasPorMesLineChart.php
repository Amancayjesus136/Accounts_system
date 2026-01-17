<?php

namespace App\Filament\Resources\Grupos\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CuentasCreadasPorMesLineChart extends ChartWidget
{
    public ?int $grupoId = null;

    public function getHeading(): string
    {
        return 'Evolución de cuentas creadas';
    }

    public static function getEmptyStateHeading(): ?string
    {
        return 'No hay historial de cuentas creadas para este grupo.';
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
            ->join('cuentas', 'cuentas.id_usuario', '=', 'asignados.id_usuario')
            ->where('asignados.id_grupo', $this->grupoId)
            ->where('asignados.estado_asignado', 1)
            ->selectRaw(
                "to_char(cuentas.created_at, 'YYYY-MM') as mes, COUNT(*) as total"
            )
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
                    'label' => 'Cuentas creadas',
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
}
