<?php

namespace App\Filament\Resources\Tokens\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TokenForm
{
    public static function configure($form)
    {
        return $form
            ->columns(12)
            ->schema([
                Section::make('Información principal')
                    ->columns(2)
                    ->columnSpan(8)
                    ->schema([
                        TextInput::make('number_token')
                            ->label('Token')
                            ->disabled()
                            ->required(),

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
                            ->native(false)
                            ->rules(['integer']),
                    ]),

                Section::make('Detalles adicionales')
                    ->columns(1)
                    ->columnSpan(4)
                    ->schema([

                        Placeholder::make('created_at')
                            ->label('Creado')
                            ->content(fn ($record): string => $record?->created_ago ?? '-'),

                        Placeholder::make('updated_at')
                            ->label('Última modificación')
                            ->content(fn ($record): string => $record?->updated_ago ?? '-'),
                    ]),

            ]);
    }
}
