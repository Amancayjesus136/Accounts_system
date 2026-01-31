<?php

namespace App\Filament\Resources\Tokens\Pages;

use App\Filament\Resources\Tokens\TokenResource;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Auth;

class ListTokens extends ListRecords
{
    protected static string $resource = TokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nueva token')
                ->icon('heroicon-o-plus')
                ->modalHeading('Crear token')
                ->modalSubmitActionLabel('Guardar')
                ->modalWidth('3xl')
                ->successNotificationTitle('La token ha sido creada correctamente')
                ->form([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('number_token')
                                ->label('Token')
                                ->required()
                                ->default(fn () => random_int(1000000, 9999999))
                                ->disabled()
                                ->dehydrated(),

                            Select::make('time_token')
                                ->label('Tiempo del token')
                                ->required()
                                ->options([
                                    1   => '1 día',
                                    7   => '7 días',
                                    30  => '30 días',
                                    90  => '90 días',
                                    0   => 'Ilimitado',
                                ])
                                ->native(false),
                        ]),
                ])
                ->mutateFormDataUsing(function (array $data) {
                    $data['estado_token'] = 1;
                    $data['id_user'] = Auth::id();
                    return $data;
                }),
        ];
    }
}
