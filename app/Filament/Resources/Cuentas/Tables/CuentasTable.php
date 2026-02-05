<?php

namespace App\Filament\Resources\Cuentas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CuentasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('id_usuario', Auth::id());
            })
            ->columns([
                TextColumn::make('plataforma.nombre_plataforma')
                    ->numeric()
                    ->label('Plataforma')
                    ->sortable(),
                TextColumn::make('visibilidad.tipo_visibilidad')
                    ->label('Visibilidad')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Publico', 'Público' => 'success',
                        'Privado' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Publico', 'Público' => 'heroicon-m-eye',
                        'Privado' => 'heroicon-m-eye-slash',
                        default => 'heroicon-m-question-mark-circle',
                    }),
                TextColumn::make('verificacion')
                    ->label('Verificación')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Verificada' : 'No verificada')
                    ->color(fn ($state) => $state ? 'info' : 'gray')
                    ->icon(fn ($state) => $state ? 'heroicon-m-shield-check' : 'heroicon-m-shield-exclamation'),
                TextColumn::make('estado_cuenta')
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
                SelectFilter::make('id_visibilidad')
                    ->label('visibilidad')
                    ->options([
                        1 => 'Publico',
                        2 => 'Privado',
                    ]),
            ])
            ->recordActions([
                EditAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
