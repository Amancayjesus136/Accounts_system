<?php

namespace App\Filament\Resources\Ingresos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class IngresosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('monto')
                    ->label('Monto')
                    ->money('PEN')
                    ->sortable()
                    ->weight('bold') ,

                TextColumn::make('categoria.nombre_categoria')
                    ->label('Categoría')
                    ->searchable()
                    ->icon(fn ($record) => $record->categoria->icono_categoria)
                    ->iconColor('primary')
                    ->sortable(),

                TextColumn::make('tarjeta.nombre_tarjeta')
                    ->label('Cuenta / Tarjeta')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->label('Fecha Registro')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
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
