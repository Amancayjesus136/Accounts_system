<?php

namespace App\Filament\Resources\Grupos\Pages;

use App\Filament\Resources\Grupos\GrupoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class ListGrupos extends ListRecords
{
    protected static string $resource = GrupoResource::class;

    public function getTabsPosition(): string
    {
        return 'before';
    }

    public function getTabsWidth(): string
    {
        return 'max-w-xs';
    }

    public function getTabs(): array
    {
        return [
            'mis_grupos' => Tab::make('Mis Grupos')
                ->icon('heroicon-m-user-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('id_user', Auth::id()))
                ->badge(static::getModel()::where('id_user', Auth::id())->count())
                ->badgeColor('primary'),

            'pertenezco' => Tab::make('Donde pertenezco')
                ->icon('heroicon-m-hand-raised')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->whereHas('asignados', function (Builder $query) {
                        $query->where('id_usuario', Auth::id())
                              ->where('estado_asignado', 1);
                    });
                })
                ->badge(
                    static::getModel()::whereHas('asignados', function ($q) {
                        $q->where('id_usuario', Auth::id())->where('estado_asignado', 1);
                    })->count()
                )
                ->badgeColor('success'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nuevo grupo')
                ->icon('heroicon-o-plus')
                ->modalHeading('Crear grupo')
                ->modalWidth('3xl')
                ->form([
                    Grid::make(1)
                        ->schema([
                            TextInput::make('nombre_grupo')
                                ->label('Nombre de grupo')
                                ->required(),
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
