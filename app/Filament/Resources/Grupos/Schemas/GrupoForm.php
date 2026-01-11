<?php

namespace App\Filament\Resources\Grupos\Schemas;

use App\Models\Visibilidad;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class GrupoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->schema([

                Section::make('Información principal')
                    ->columns(1)
                    ->columnSpan(8)
                    ->schema([
                        // Select::make('id_visibilidad')
                        //     ->label('Visibilidad')
                        //     ->options(
                        //         Visibilidad::where('estado_visibilidad', 1)
                        //             ->pluck('tipo_visibilidad', 'id_visibilidad')
                        //     )
                        //     ->searchable()
                        //     ->preload()
                        //     ->required(),

                        TextInput::make('nombre_grupo')
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
