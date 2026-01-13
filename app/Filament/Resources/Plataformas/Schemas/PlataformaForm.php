<?php

namespace App\Filament\Resources\Plataformas\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PlataformaForm
{
    public static function configure($form)
    {
        return $form
            ->columns(12)
            ->schema([

                Section::make('Información principal')
                    ->columns(3)
                    ->columnSpan(8)
                    ->schema([
                        TextInput::make('grupo_plataforma')
                            ->label('Grupo de plataforma')
                            ->required(),

                        TextInput::make('entidad_plataforma')
                            ->label('Entidad de plataforma')
                            ->required(),

                        TextInput::make('nombre_plataforma')
                            ->label('Nombre de plataforma')
                            ->required(),
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
