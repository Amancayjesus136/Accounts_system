<?php

namespace App\Filament\Resources\Visibilidads\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class VisibilidadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([

                Section::make('')
                    ->columnSpanFull()
                    ->columns(1)
                    ->schema([
                        TextInput::make('tipo_visibilidad')
                        ->required(),
                    ]),

            ]);
    }
}


