<?php

namespace App\Filament\Resources\Tarjetas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Infolist;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\FontWeight;

class TarjetaInfolist
{
    public static function configure($infolist)
    {
        return $infolist
            ->schema([

                Section::make('Detalles Financieros')
                    ->icon('heroicon-o-credit-card')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('nombre_tarjeta')
                            ->label('Nombre de la Cuenta')
                            ->icon('heroicon-m-identification')
                            ->weight(FontWeight::Bold),

                        TextEntry::make('tipo_tarjeta')
                            ->label('Tipo')
                            ->badge()
                            ->color('info')
                            ->icon('heroicon-m-banknotes'),

                        TextEntry::make('monto.monto_tarjeta')
                            ->label('Saldo Inicial / Monto')
                            ->money('PEN')
                            ->weight(FontWeight::Bold)
                            ->color('success'),

                        TextEntry::make('estado_tarjeta')
                            ->label('Estado')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => $state == '1' ? 'Activo' : 'Inactivo')
                            ->color(fn (string $state): string => $state == '1' ? 'success' : 'danger')
                            ->icon(fn (string $state): string => $state == '1' ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle'),
                    ]),

                Section::make('Información de Registro')
                    ->icon('heroicon-o-clock')
                    ->collapsed()
                    ->columns(3)
                    ->schema([
                        TextEntry::make('id_usuario')
                            ->label('ID Usuario')
                            ->icon('heroicon-m-user'),

                        TextEntry::make('created_at')
                            ->label('Fecha de Creación')
                            ->dateTime('d/m/Y H:i A'),

                        TextEntry::make('updated_at')
                            ->label('Última Actualización')
                            ->since(),
                    ]),
            ]);
    }
}
