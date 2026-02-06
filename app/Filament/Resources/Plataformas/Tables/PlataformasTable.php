<?php

namespace App\Filament\Resources\Plataformas\Tables;

use App\Filament\Resources\Plataformas\PlataformaResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class PlataformasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query->whereIn('id_usuario', [1, Auth::id()]);
            })

            ->defaultSort('estado_plataforma', 'desc')

            ->recordUrl(
                fn ($record) => $record->id_usuario === Auth::id()
                    ? PlataformaResource::getUrl('edit', ['record' => $record])
                    : null
            )
            ->columns([
                TextColumn::make('grupo_plataforma')
                    ->searchable(),

                TextColumn::make('entidad_plataforma')
                    ->searchable(),

                TextColumn::make('nombre_plataforma')
                    ->searchable(),

                TextColumn::make('estado_plataforma')
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
                EditAction::make()
                    ->visible(fn ($record) => $record->id_usuario === Auth::id()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
