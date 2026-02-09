<?php

namespace App\Filament\Resources\Categorias\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CategoriasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('id_usuario', Auth::id());
            })
            ->columns([
                TextColumn::make('nombre_categoria')
                    ->label('Nombre')
                    ->searchable(),

                TextColumn::make('icono_categoria')
                    ->label('Icono')
                    ->icon(fn (string $state): string => $state)

                    ->iconColor('primary')

                    ->formatStateUsing(fn () => null)

                    ->alignCenter()
                    ->searchable(),

                TextColumn::make('tipo_categoria')
                    ->label('Tipo')
                    ->searchable(),

                TextColumn::make('estado_categoria')
                    ->label('Estado')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state) => $state === 1 ? 'success' : 'danger')
                    ->formatStateUsing(fn (int $state) => $state === 1 ? 'Activo' : 'Inactivo'),

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
