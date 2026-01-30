<?php

namespace App\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Livewire\Component;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Models\Cuenta;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class CuentasUsuarioTable extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithActions;

    public Model $record;

    public function table(Table $table): Table
    {
        $mensajePrivacidad = 'Como es privado no se puede ver este campo. Solicita que lo conviertan en público para visualizarlo.';

        return $table
            ->query(function () {
                if ($this->record->estado_asignado !== 1) {
                    return Cuenta::query()->whereRaw('1 = 0');
                }
                $usuarioId = $this->record->usuario?->id ?? $this->record->id_usuario;
                return Cuenta::query()->where('id_usuario', $usuarioId);
            })
            ->columns([
                TextColumn::make('plataforma.nombre_plataforma')
                    ->label('Plataforma')
                    ->weight('bold')
                    ->icon('heroicon-m-computer-desktop')
                    ->searchable(['nombre_plataforma', 'grupo_plataforma', 'entidad_plataforma']),

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

                TextColumn::make('cuentaUsuario.username_cuenta')
                    ->label('Usuario')
                    ->weight('bold')
                    ->searchable()
                    ->icon(fn ($state, $record) =>
                        ($record->visibilidad?->id_visibilidad === 2) ? 'heroicon-m-lock-closed' : (
                            empty($state) ? 'heroicon-m-x-mark' : 'heroicon-m-user'
                        )
                    )
                    ->color(fn ($state, $record) =>
                        ($record->visibilidad?->id_visibilidad === 2) ? 'danger' : (
                            empty($state) ? 'gray' : null
                        )
                    )
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->visibilidad?->tipo_visibilidad === 'Privado' || $record->visibilidad?->id_visibilidad === 2) {
                            return '🔒 Restringido';
                        }
                        if (empty($state)) {
                            return 'Sin registro';
                        }
                        return $state;
                    })
                    ->tooltip(fn ($record) => ($record->visibilidad?->id_visibilidad === 2) ? $mensajePrivacidad : null),

                TextColumn::make('cuentaUsuario.number_cuenta')
                    ->label('Número')
                    ->weight('bold')
                    ->searchable()
                    ->icon(fn ($state, $record) =>
                        ($record->visibilidad?->id_visibilidad === 2) ? 'heroicon-m-lock-closed' : (
                            empty($state) ? 'heroicon-m-x-mark' : 'heroicon-m-phone'
                        )
                    )
                    ->color(fn ($state, $record) =>
                        ($record->visibilidad?->id_visibilidad === 2) ? 'danger' : (
                            empty($state) ? 'gray' : null
                        )
                    )
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->visibilidad?->tipo_visibilidad === 'Privado' || $record->visibilidad?->id_visibilidad === 2) {
                            return '🔒 Restringido';
                        }
                        if (empty($state)) {
                            return 'Sin registro';
                        }
                        return $state;
                    })
                    ->tooltip(fn ($record) => ($record->visibilidad?->id_visibilidad === 2) ? $mensajePrivacidad : null),

                TextColumn::make('cuentaUsuario.email_cuenta')
                    ->label('Email')
                    ->weight('bold')
                    ->searchable()
                    ->icon(fn ($state, $record) =>
                        ($record->visibilidad?->id_visibilidad === 2) ? 'heroicon-m-lock-closed' : (
                            empty($state) ? 'heroicon-m-x-mark' : 'heroicon-m-envelope'
                        )
                    )
                    ->color(fn ($state, $record) =>
                        ($record->visibilidad?->id_visibilidad === 2) ? 'danger' : (
                            empty($state) ? 'gray' : null
                        )
                    )
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->visibilidad?->tipo_visibilidad === 'Privado' || $record->visibilidad?->id_visibilidad === 2) {
                            return '🔒 Restringido';
                        }
                        if (empty($state)) {
                            return 'Sin registro';
                        }
                        return $state;
                    })
                    ->copyable(fn ($state, $record) => !($record->visibilidad?->id_visibilidad === 2) && !empty($state))
                    ->tooltip(fn ($record) => ($record->visibilidad?->id_visibilidad === 2) ? $mensajePrivacidad : null),

                TextColumn::make('cuentaUsuario.password_cuenta')
                    ->label('Contraseña')
                    ->weight('bold')
                    ->icon(fn ($state, $record) =>
                        ($record->visibilidad?->id_visibilidad === 2) ? null : (
                            empty($state) ? 'heroicon-m-x-mark' : 'heroicon-m-key'
                        )
                    )
                    ->color(fn ($state, $record) =>
                        ($record->visibilidad?->id_visibilidad === 2) ? 'danger' : (
                            empty($state) ? 'gray' : null
                        )
                    )
                    ->formatStateUsing(function ($state, $record) {
                        $esPrivado = $record->visibilidad?->tipo_visibilidad === 'Privado' || $record->visibilidad?->id_visibilidad === 2;
                        if ($esPrivado) {
                            return '🔒 Restringido';
                        }

                        if (empty($state)) {
                            return 'Sin registro';
                        }

                        try {
                            return Crypt::decryptString($state);
                        } catch (DecryptException $e) {
                            return 'Error';
                        }
                    })
                    ->copyable(fn ($state, $record) => !($record->visibilidad?->id_visibilidad === 2) && !empty($state))
                    ->copyMessage('Contraseña copiada')
                    ->tooltip(fn ($record) => ($record->visibilidad?->id_visibilidad === 2) ? $mensajePrivacidad : null),
            ])
            ->filters([
                SelectFilter::make('id_visibilidad')
                    ->label('visibilidad')
                    ->options([
                        1 => 'Publico',
                        2 => 'Privado',
                    ]),
            ])
            ->paginated(false);
    }

    public function render()
    {
        return view('livewire.cuentas-usuario-table');
    }
}
