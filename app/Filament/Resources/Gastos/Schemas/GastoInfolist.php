<?php

namespace App\Filament\Resources\Gastos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class GastoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('descripcion')
                    ->columnSpanFull(),
                TextEntry::make('monto')
                    ->numeric(),
                TextEntry::make('id_usuario')
                    ->numeric(),
                TextEntry::make('id_tarjeta')
                    ->numeric(),
                TextEntry::make('id_categoria')
                    ->numeric(),
                TextEntry::make('estado_ingreso')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
