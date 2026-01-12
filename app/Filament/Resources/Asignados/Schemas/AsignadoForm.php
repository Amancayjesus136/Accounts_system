<?php

namespace App\Filament\Resources\Asignados\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Components\Tabs\Tab;

class AsignadoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_grupo')
                    ->required()
                    ->numeric(),
                TextInput::make('id_usuario')
                    ->required()
                    ->numeric(),
                TextInput::make('estado_asignado')
                    ->required()
                    ->numeric(),
            ]);
    }
}
