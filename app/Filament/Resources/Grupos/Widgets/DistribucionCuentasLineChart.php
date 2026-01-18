<?php

namespace App\Filament\Resources\Grupos\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DistribucionCuentasLineChart extends ChartWidget
{
    public ?int $grupoId = null;

    public function getHeading(): string
    {
        return 'Evolución diaria de cuentas (Integrantes y Propietario)';
    }

    public static function getEmptyStateHeading(): ?string
    {
        return 'No hay historial de cuentas para los miembros de este grupo.';
    }

    protected function getData(): array
    {
        if (! $this->grupoId) {
            return ['datasets' => [], 'labels' => []];
        }

        /**
         * 1. Unificamos al dueño del grupo y a los integrantes asignados
         */
        $participantesQuery = DB::table('grupos')
            ->where('id_grupo', $this->grupoId)
            ->select('id_user as user_id') // El dueño
            ->union(
                DB::table('asignados')
                    ->where('id_grupo', $this->grupoId)
                    ->where('estado_asignado', 1)
                    ->select('id_usuario as user_id') // Los asignados
            );

        /**
         * 2. Obtenemos los datos cruzando con la lista unificada
         */
        $rawData = DB::table('users')
            ->joinSub($participantesQuery, 'participantes', function ($join) {
                $join->on('users.id', '=', 'participantes.user_id');
            })
            ->join('cuentas', 'cuentas.id_usuario', '=', 'users.id')
            ->selectRaw("
                users.name as usuario,
                to_char(cuentas.created_at, 'YYYY-MM-DD') as fecha,
                COUNT(cuentas.id_cuenta) as total
            ")
            ->groupBy('usuario', 'fecha')
            ->orderBy('fecha')
            ->get();

        if ($rawData->isEmpty()) {
            return ['datasets' => [], 'labels' => []];
        }

        // 3. Eje X: Fechas únicas detectadas
        $labels = $rawData->pluck('fecha')->unique()->sort()->values()->toArray();

        // 4. Generar una línea por cada usuario (incluyendo al dueño)
        $datasets = [];
        $usuarios = $rawData->groupBy('usuario');
        $colors = ['#8b5cf6', '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#ec4899', '#06b6d4'];
        $index = 0;

        foreach ($usuarios as $nombre => $registros) {
            $dataPorFecha = $registros->pluck('total', 'fecha');
            $puntos = [];

            foreach ($labels as $fecha) {
                // Si el usuario no tiene registros ese día, marcamos 0 para no romper la línea
                $puntos[] = $dataPorFecha->get($fecha, 0);
            }

            $color = $colors[$index % count($colors)];

            $datasets[] = [
                'label' => $nombre,
                'data' => $puntos,
                'borderColor' => $color,
                'backgroundColor' => $color,
                'fill' => false,
                'tension' => 0.4,
                'pointRadius' => 4,
            ];
            $index++;
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
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => ['stepSize' => 1],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
