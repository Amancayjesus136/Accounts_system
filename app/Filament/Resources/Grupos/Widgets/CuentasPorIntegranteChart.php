<?php

namespace App\Filament\Resources\Grupos\Widgets;

use Filament\Actions\Concerns\InteractsWithRecord;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\DB;

class CuentasPorIntegranteChart extends ChartWidget
{
    public ?int $grupoId = null;

    public function getHeading(): string { return 'Cuentas por integrante'; }

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
                'users.name as usuario',
                DB::raw('COUNT(cuentas.id_cuenta) as total')
            )
            ->groupBy('users.name')
            ->orderByDesc('total')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total de cuentas',
                    'data' => $data->pluck('total')->toArray(),
                ],
            ],
            'labels' => $data->pluck('usuario')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

