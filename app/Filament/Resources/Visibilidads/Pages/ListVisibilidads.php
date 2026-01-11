<?php

namespace App\Filament\Resources\Visibilidads\Pages;

use App\Filament\Resources\Visibilidads\VisibilidadResource;
use Filament\Actions\Action as ActionsAction;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
// use Filament\Actions\Action;
use Filament\Tables\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListVisibilidads extends ListRecords
{
    protected static string $resource = VisibilidadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nueva visibilidad')
                ->icon('heroicon-o-plus')
                ->modalHeading('Crear visibilidad')
                ->modalSubmitActionLabel('Guardar')
                ->modalWidth('3xl')
                ->successNotificationTitle('La visibilidad ha sido creada correctamente')
                ->form([
                    TextInput::make('tipo_visibilidad')
                        ->label('Tipo de visibilidad')
                        ->required(),
                ])
                ->mutateFormDataUsing(function (array $data) {
                    $data['estado_visibilidad'] = 1;
                    return $data;
                }),
        ];
    }

}
