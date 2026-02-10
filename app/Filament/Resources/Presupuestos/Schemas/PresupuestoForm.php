<?php

namespace App\Filament\Resources\Presupuestos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PresupuestoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_usuario')
                    ->required()
                    ->numeric(),
                TextInput::make('id_categoria')
                    ->required()
                    ->numeric(),
                TextInput::make('monto_presupuesto')
                    ->required()
                    ->numeric(),
                TextInput::make('estado_presupuesto')
                    ->required()
                    ->numeric(),
            ]);
    }
}
