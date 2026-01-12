<?php

namespace App\Filament\Resources\Asignados\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class AsignadosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('id_usuario', Auth::id()))

            ->columns([
                TextColumn::make('usuario.name')
                    ->label('Nombre de integrante')
                    ->searchable(),
                TextColumn::make('usuario.email')
                    ->label('Correo')
                    ->searchable(),
                TextColumn::make('estado_asignado')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => 'Aceptado',
                        2 => 'Pendiente',
                        0 => 'Rechazado',
                        default => 'Desconocido',
                    })
                    ->color(fn (int $state): string => match ($state) {
                        1 => 'success',
                        2 => 'warning',
                        0 => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (int $state): string => match ($state) {
                        1 => 'heroicon-o-check-circle',
                        2 => 'heroicon-o-clock',
                        0 => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    }),
                TextColumn::make('created_at')
                    ->label('Asignado el')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
