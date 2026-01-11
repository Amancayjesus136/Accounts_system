<?php

namespace App\Filament\Resources\Grupos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GrupoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_visibilidad')
                    ->required(),
                TextInput::make('id_user')
                    ->required(),
                TextInput::make('nombre_grupo')
                    ->required(),
                TextInput::make('estado_grupo')
                    ->required(),
            ]);
    }
}
