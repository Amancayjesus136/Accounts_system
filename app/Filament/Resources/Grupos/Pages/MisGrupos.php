<?php

namespace App\Filament\Resources\Grupos\Pages;

use App\Filament\Clusters\GruposCluster;
use App\Filament\Resources\Grupos\GrupoResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use BackedEnum;

class MisGrupos extends BaseGrupoPage
{
    protected static string $resource = GrupoResource::class;
    protected static ?string $cluster = GruposCluster::class;

    protected static ?string $title = 'Donde pertenezco';
    protected static ?string $navigationLabel = 'Donde pertenezco';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-hand-raised';

    public function getSubNavigation(): array
    {
        return static::getResource()::getRecordSubNavigation($this);
    }

    protected function getTableQuery(): ?Builder
    {
        return parent::getTableQuery()
            ->whereHas('asignados', function (Builder $query) {
                $query->where('id_usuario', Auth::id())
                      ->where('estado_asignado', 1);
            });
    }
}
