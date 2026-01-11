<?php

namespace App\Filament\Resources\Visibilidads\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Schemas\Components\Section;

class VisibilidadForm
{
    public static function configure($form)
    {
        return $form
            ->columns(12)
            ->schema([

                Section::make('Información principal')
                    ->columns(1)
                    ->columnSpan(8)
                    ->schema([
                        TextInput::make('tipo_visibilidad')
                            ->label('Tipo de visibilidad')
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
