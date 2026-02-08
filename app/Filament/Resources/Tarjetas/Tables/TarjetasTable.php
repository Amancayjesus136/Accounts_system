<?php

namespace App\Filament\Resources\Tarjetas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TarjetasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tipo_tarjeta')
                    ->searchable(),

                TextColumn::make('ultimoMonto.monto_tarjeta')
                    ->label('Saldo Actual')
                    ->money('PEN')
                    ->alignEnd()
                    ->weight('bold')
                    ->color('success')
                    ->placeholder('S/. 0.00'),

                TextColumn::make('nombre_tarjeta')
                    ->label('Tipo de Cuenta')
                    ->numeric()
                    ->badge()
                    ->sortable(),
                TextColumn::make('estado_tarjeta')
                    ->numeric()
                    ->label('Estado')
                    ->searchable()
                    ->badge()
                    ->icon(fn (int $state) => $state === 1 ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->formatStateUsing(fn (int $state) => $state === 1 ? 'Activo' : 'Inactivo')
                    ->color(fn (int $state) => $state === 1 ? 'success' : 'danger')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
