<?php

namespace App\Filament\Resources\Asignados\Tables;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
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
              TextColumn::make('grupo.owner.name')
                ->label('Nombre del solicitante')
                ->searchable(),

            TextColumn::make('grupo.owner.email')
                ->label('Correo del solicitante')
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
            ->actions([
                Action::make('aceptar')
                    ->label('Aceptar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->estado_asignado === 2)
                    ->requiresConfirmation()
                    ->action(function ($record, $livewire) {
                        $record->update(['estado_asignado' => 1]);
                        $livewire->dispatch('refreshTabs');

                        $duenoDelGrupo = $record->grupo?->id_user ? User::find($record->grupo->id_user) : null;

                        $usuarioActual = Auth::user();
                        $nombreUsuarioQueAcepta = $usuarioActual ? $usuarioActual->name : 'Un usuario';

                        if ($duenoDelGrupo) {
                            Notification::make()
                                ->title('Solicitud Aceptada')
                                ->body("{$nombreUsuarioQueAcepta} se ha unido al grupo: {$record->grupo->nombre_grupo}.")
                                ->icon('heroicon-o-check-circle')
                                ->success()
                                ->sendToDatabase($duenoDelGrupo);
                        }

                        Notification::make()
                            ->title('Has aceptado la solicitud')
                            ->success()
                            ->send();
                    }),

                Action::make('rechazar')
                    ->label('Rechazar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->estado_asignado === 2)
                    ->requiresConfirmation()
                    ->action(function ($record, $livewire) {
                        $record->update(['estado_asignado' => 0]);
                        $livewire->dispatch('refreshTabs');

                        $duenoDelGrupo = $record->grupo?->id_user ? User::find($record->grupo->id_user) : null;

                        $usuarioActual = Auth::user();
                        $nombreUsuarioQueRechaza = $usuarioActual ? $usuarioActual->name : 'Un usuario';

                        if ($duenoDelGrupo) {
                            Notification::make()
                                ->title('Solicitud Rechazada')
                                ->body("{$nombreUsuarioQueRechaza} rechazó la invitación al grupo: {$record->grupo->nombre_grupo}.")
                                ->icon('heroicon-o-x-circle')
                                ->danger()
                                ->sendToDatabase($duenoDelGrupo);
                        }

                        Notification::make()
                            ->title('Has rechazado la solicitud')
                            ->warning()
                            ->send();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
