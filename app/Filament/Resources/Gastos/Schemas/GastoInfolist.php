<?php

namespace App\Filament\Resources\Gastos\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class GastoInfolist
{
    public static function configure($form)
    {
        return $form
            ->columns(12)
            ->schema([

                Section::make('Información principal')
                    ->columns(3)
                    ->columnSpan(8)
                    ->schema([
                        TextEntry::make('monto')
                            ->label('Monto Total')
                            ->money('PEN')
                            ->weight(FontWeight::Bold)
                            ->color('danger'),

                        TextEntry::make('categoria.nombre_categoria')
                            ->label('Categoría')
                            ->icon(fn ($record) => $record->categoria->icono_categoria ?? 'heroicon-o-tag')
                            ->badge()
                            ->color('primary'),

                        TextEntry::make('tarjeta.nombre_tarjeta')
                            ->label('Método de Pago')
                            ->icon('heroicon-o-credit-card'),
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
