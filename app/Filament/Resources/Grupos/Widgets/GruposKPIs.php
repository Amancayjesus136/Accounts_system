<?php

namespace App\Filament\Resources\Grupos\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Grupo;
use Illuminate\Support\Facades\DB;

class GruposKPIs extends StatsOverviewWidget
{
    protected ?string $heading = 'KPIs del Grupo';

    public ?Grupo $grupo = null;

    protected function getStats(): array
    {
        if (!$this->grupo) {
            return [];
        }

        return [
            Stat::make('Total de integrantes', $this->grupo->asignados()->where('estado_asignado', 1)->count())
                ->description('Integrantes activos')
                ->color('success'),

            Stat::make('Total de cuentas', function () {
                return DB::table('cuentas')
                    ->join('asignados', 'asignados.id_usuario', '=', 'cuentas.id_usuario')
                    ->where('asignados.id_grupo', $this->grupo->id_grupo)
                    ->where('asignados.estado_asignado', 1)
                    ->count();
            })
            ->description('Cuentas activas')
            ->color('primary'),

            Stat::make('Promedio de cuentas por integrante', function () {
                $totalCuentas = DB::table('cuentas')
                    ->join('asignados', 'asignados.id_usuario', '=', 'cuentas.id_usuario')
                    ->where('asignados.id_grupo', $this->grupo->id_grupo)
                    ->where('asignados.estado_asignado', 1)
                    ->count();

                $totalIntegrantes = $this->grupo->asignados()->where('estado_asignado', 1)->count();

                return $totalIntegrantes > 0 ? round($totalCuentas / $totalIntegrantes, 2) : 0;
            })
            ->description('Cuentas promedio')
            ->color('warning'),
        ];
    }
}
