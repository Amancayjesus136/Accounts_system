<?php

namespace App\Filament\Resources\Plataformas\Pages;

use App\Filament\Resources\Plataformas\PlataformaResource;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Auth;

class ListPlataformas extends ListRecords
{
    protected static string $resource = PlataformaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nueva plataforma')
                ->icon('heroicon-o-plus')
                ->modalHeading('Crear plataforma')
                ->modalSubmitActionLabel('Guardar')
                ->modalWidth('3xl')
                ->successNotificationTitle('La plataforma ha sido creada correctamente')
                ->form([
                    Grid::make(3)
                        ->schema([
                            TextInput::make('grupo_plataforma')
                                ->label('Grupo de la plataforma')
                                ->required(),

                            TextInput::make('entidad_plataforma')
                                ->label('Entidad de la plataforma')
                                ->required(),

                            TextInput::make('nombre_plataforma')
                                ->label('Nombre de la plataforma')
                                ->unique()
                                ->required(),
                        ]),
                ])
                ->mutateFormDataUsing(function (array $data) {
                    $data['estado_plataforma'] = 1;
                    $data['id_usuario'] = Auth::id();
                    return $data;
                }),
        ];
    }

}
