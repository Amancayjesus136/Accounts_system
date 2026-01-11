<?php

namespace App\Filament\Resources\Grupos\Pages;

use App\Filament\Resources\Grupos\GrupoResource;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Auth;

class ListGrupos extends ListRecords
{
    protected static string $resource = GrupoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nuevo grupo')
                ->icon('heroicon-o-plus')
                ->modalHeading('Crear grupo')
                ->modalSubmitActionLabel('Guardar')
                ->modalWidth('3xl')
                ->successNotificationTitle('El grupo ha sido creada correctamente')
                ->form([
                    Grid::make(1)
                        ->schema([
                            // Select::make('id_visibilidad')
                            //     ->label('Tipo visibilidad')
                            //     ->relationship(
                            //         name: 'visibilidad',
                            //         titleAttribute: 'tipo_visibilidad',
                            //         modifyQueryUsing: fn ($query) => $query->where('estado_visibilidad', 1)
                            //     )
                            //     ->required()
                            //     ->preload()
                            //     ->searchable()
                            //     ->columnSpan(1),

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
