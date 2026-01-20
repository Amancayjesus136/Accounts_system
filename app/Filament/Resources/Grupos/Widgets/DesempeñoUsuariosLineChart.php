<?php

namespace App\Filament\Resources\Grupos\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use App\Models\Grupo;

class DesempeñoUsuariosLineChart extends ChartWidget
{
    public ?int $grupoId = null;

    public function getHeading(): string
    {
        return 'Comparativa de cuentas por usuario';
    }

    public static function getEmptyStateHeading(): ?string
    {
        return 'No hay usuarios o cuentas registradas en este grupo.';
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
            return ['datasets' => [], 'labels' => []];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total de cuentas',
                    'data' => $data->pluck('total')->toArray(),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                    'pointRadius' => 5,
                    'pointBackgroundColor' => '#10b981',
                ],
            ],
            'labels' => $data->pluck('usuario')->toArray(),
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
                    'ticks' => ['stepSize' => 1],
                ],
            ],
        ];
    }
}
