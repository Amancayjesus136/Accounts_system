<?php

namespace App\Filament\Resources\Grupos\Schemas;

use App\Models\Visibilidad;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class GrupoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('id_visibilidad')
                    ->label('Visibilidad')
                    ->options(
                        Visibilidad::where('estado_visibilidad', 1)
                            ->pluck('tipo_visibilidad', 'id_visibilidad')
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('nombre_grupo')
                    ->required(),
            ]);
    }
}
