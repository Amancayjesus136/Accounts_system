<?php

namespace App\Filament\Resources\Grupos\Pages;

use App\Filament\Clusters\GruposCluster;
use App\Filament\Resources\Grupos\GrupoResource;
use App\Filament\Resources\Grupos\Widgets\CuentasCreadasPorDiaLineChart;
use App\Filament\Resources\Grupos\Widgets\CuentasCreadasPorMesLineChart;
use App\Filament\Resources\Grupos\Widgets\CuentasPorIntegranteChart;
use App\Filament\Resources\Grupos\Widgets\CuentasPorIntegrantePieChart;
use App\Filament\Resources\Grupos\Widgets\DesempeñoUsuariosLineChart;
use App\Filament\Resources\Grupos\Widgets\DistribucionCuentasBarChart;
use App\Filament\Resources\Grupos\Widgets\DistribucionCuentasLineChart;
use App\Filament\Resources\Grupos\Widgets\GruposKPIs;
use App\Filament\Resources\Grupos\Widgets\IntegrantesVsCuentasBarChart;
use App\Models\Grupo;
use Filament\Resources\Pages\Page;
use BackedEnum;

class GruposEstadisticas extends Page
{
    protected static string $resource = GrupoResource::class;
    protected static ?string $cluster = GruposCluster::class;

    protected static ?string $title = 'Estadísticas del grupo';
    protected static ?string $navigationLabel = 'Estadísticas';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    public Grupo $record;

    public function mount(Grupo $record): void
    {
        $this->record = $record;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            GruposKPIs::make([
                'grupo' => $this->record,
            ]),

            DesempeñoUsuariosLineChart::make(['grupoId' => $this->record->id_grupo]),
            CuentasPorIntegrantePieChart::make(['grupoId' => $this->record->id_grupo]),
            CuentasCreadasPorDiaLineChart::make(['grupoId' => $this->record->id_grupo]),
            CuentasCreadasPorMesLineChart::make(['grupoId' => $this->record->id_grupo]),
        ];
    }

}
