<?php

namespace App\Filament\Resources\Grupos\Pages;

use App\Filament\Clusters\GruposCluster;
use App\Filament\Resources\Grupos\GrupoResource;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;

class ListGrupos extends BaseGrupoPage
{
    protected static string $resource = GrupoResource::class;
    protected static ?string $cluster = GruposCluster::class;

    protected static ?string $title = 'Mis Grupos';
    protected static ?string $navigationLabel = 'Mis Grupos';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    public function getSubNavigation(): array
    {
        return static::getResource()::getRecordSubNavigation($this);
    }

    protected function getTableQuery(): ?Builder
    {
        return parent::getTableQuery()
            ->where('id_user', Auth::id());
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nuevo grupo')
                ->icon('heroicon-o-plus')
                ->modalWidth('3xl')
                ->form([
                    Grid::make(1)->schema([
                        TextInput::make('nombre_grupo')->required()->unique(),
                    ]),
                ])
                ->mutateFormDataUsing(function (array $data) {
                    $data['estado_grupo'] = 1;
                    $data['id_visibilidad'] = 2;
                    $data['id_user'] = Auth::id();
                    return $data;
                }),
        ];
    }
}
