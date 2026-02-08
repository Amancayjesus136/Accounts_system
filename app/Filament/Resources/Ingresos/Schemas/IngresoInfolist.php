<?php

namespace App\Filament\Resources\Ingresos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class IngresoInfolist
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
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
