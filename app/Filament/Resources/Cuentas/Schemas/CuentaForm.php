<?php

namespace App\Filament\Resources\Cuentas\Schemas;

use App\Models\Plataforma;
use App\Models\Visibilidad;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Crypt;

class CuentaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->schema([

                Section::make('Información general')
                    ->description('Datos de tipo de cuenta, descriptivos y estado')
                    ->columns(12)
                    ->columnSpan(12)
                    ->schema([

                        Select::make('grupo_plataforma')
                            ->label('Grupo de plataforma')
                            ->columnSpan(4)
                            ->options(
                                Plataforma::query()
                                    ->distinct()
                                    ->pluck('grupo_plataforma', 'grupo_plataforma')
                            )
                            ->reactive()
                            ->required()
                            ->afterStateUpdated(fn (callable $set) => [
                                $set('entidad_plataforma', null),
                                $set('id_plataforma', null),
                            ]),

                        Select::make('entidad_plataforma')
                            ->label('Entidad')
                            ->columnSpan(4)
                            ->reactive()
                            ->required()
                            ->options(fn (callable $get) =>
                                $get('grupo_plataforma')
                                    ? Plataforma::where('grupo_plataforma', $get('grupo_plataforma'))
                                        ->distinct()
                                        ->pluck('entidad_plataforma', 'entidad_plataforma')
                                    : []
                            )
                            ->afterStateUpdated(fn (callable $set) => $set('id_plataforma', null)),

                        Select::make('id_plataforma')
                            ->label('Plataforma')
                            ->columnSpan(4)
                            ->searchable()
                            ->required()
                            ->options(fn (callable $get) =>
                                $get('grupo_plataforma') && $get('entidad_plataforma')
                                    ? Plataforma::where('grupo_plataforma', $get('grupo_plataforma'))
                                        ->where('entidad_plataforma', $get('entidad_plataforma'))
                                        ->pluck('nombre_plataforma', 'id_plataforma')
                                    : []
                            )
                            ->afterStateHydrated(function ($state, callable $set) {
                                if (! $state) {
                                    return;
                                }

                                $plataforma = Plataforma::find($state);

                                if ($plataforma) {
                                    $set('grupo_plataforma', $plataforma->grupo_plataforma);
                                    $set('entidad_plataforma', $plataforma->entidad_plataforma);
                                }
                            }),

                        RichEditor::make('descripcion')
                            ->label('Descripción')
                            ->columnSpan(8)
                            ->toolbarButtons([
                                ['bold', 'italic', 'underline'],
                                ['alignStart', 'alignCenter', 'alignEnd'],
                                ['undo', 'redo'],
                            ]),

                        Select::make('id_visibilidad')
                            ->label('Visibilidad')
                            ->columnSpan(2)
                            ->options(
                                Visibilidad::query()
                                    ->where('estado_visibilidad', 1)
                                    ->pluck('tipo_visibilidad', 'id_visibilidad')
                            )
                            ->searchable()
                            ->required(),

                        Toggle::make('verificacion')
                            ->label('Cuenta verificada')
                            ->columnSpan(2)
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger')
                            ->default(false),
                    ]),

                Section::make('Credenciales de acceso')
                    ->description('Datos asociados al usuario de la cuenta')
                    ->columns(4)
                    ->columnSpan(12)
                    ->schema([

                        TextInput::make('cuenta_usuario.username_cuenta')
                            ->label('Usuario')
                            ->maxLength(100),

                        TextInput::make('cuenta_usuario.email_cuenta')
                            ->label('Correo electrónico')
                            ->email()
                            ->maxLength(150),

                        TextInput::make('cuenta_usuario.number_cuenta')
                            ->label('Número')
                            ->maxLength(50),

                        TextInput::make('cuenta_usuario.password_cuenta')
                            ->label('Contraseña')
                            ->required(fn (string $context) => $context === 'create')
                            ->password()
                            ->revealable()
                            ->disabled(fn (string $context) =>
                                $context === 'edit' &&
                                ! session()->get('cuenta_validada_' . request()->route('record'), false)
                            )
                            ->helperText(fn (string $context) =>
                                $context === 'edit'
                                    ? 'Usa el botón "Ingresar Credenciales" y luego el ojito para visualizar'
                                    : ''
                            )
                            ->dehydrated(fn (string $context, $state) => $context === 'create' || (!empty($state) && $state !== '********'))
                            ->afterStateHydrated(function ($state, callable $set) {
                                $recordId = request()->route('record');
                                if (session()->get("cuenta_validada_{$recordId}", false)) {
                                    $cuentaUsuario = \App\Models\CuentaUsuario::where('id_cuenta', $recordId)->first();
                                    if ($cuentaUsuario && $cuentaUsuario->password_cuenta) {
                                        try {
                                            $decrypted = Crypt::decryptString($cuentaUsuario->password_cuenta);
                                            $set('cuenta_usuario.password_cuenta', $decrypted);
                                        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                                            $set('cuenta_usuario.password_cuenta', null);
                                        }
                                    }
                                }
                            }),
                    ]),

            ]);
    }
}
