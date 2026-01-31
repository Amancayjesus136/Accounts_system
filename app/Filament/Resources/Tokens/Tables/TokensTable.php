<?php

namespace App\Filament\Resources\Tokens\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TokensTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number_token')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('time_token')
                    ->label('Tiempo')
                    ->sortable()
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1   => '1 día',
                        7   => '7 días',
                        30  => '30 días',
                        90  => '90 días',
                        0   => 'Ilimitado',
                        default => $state . ' días',
                    }),
                TextColumn::make('estado_token')
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
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
