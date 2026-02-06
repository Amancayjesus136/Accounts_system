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
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;

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
                            ->options(Plataforma::query()->distinct()->pluck('grupo_plataforma', 'grupo_plataforma'))
                            ->reactive()
                            ->required()
                            ->hiddenOn('view')
                            ->afterStateUpdated(function (callable $set) {
                                $set('entidad_plataforma', null);
                                $set('id_plataforma', null);
                            }),

                        Select::make('entidad_plataforma')
                            ->label('Entidad')
                            ->columnSpan(4)
                            ->reactive()
                            ->required()
                            ->hiddenOn('view')
                            ->options(fn (callable $get) => $get('grupo_plataforma') ? Plataforma::where('grupo_plataforma', $get('grupo_plataforma'))->distinct()->pluck('entidad_plataforma', 'entidad_plataforma') : [])
                            ->afterStateUpdated(fn (callable $set) => $set('id_plataforma', null)),

                        Select::make('id_plataforma')
                            ->label('Plataforma')
                            ->columnSpan(4)
                            ->searchable()
                            ->required()
                            ->hiddenOn('view')
                            ->options(fn (callable $get) => $get('grupo_plataforma') && $get('entidad_plataforma') ? Plataforma::where('grupo_plataforma', $get('grupo_plataforma'))->where('entidad_plataforma', $get('entidad_plataforma'))->pluck('nombre_plataforma', 'id_plataforma') : [])
                            ->afterStateHydrated(function ($state, callable $set) {
                                if (!$state) return;
                                $plataforma = Plataforma::find($state);
                                if ($plataforma) {
                                    $set('grupo_plataforma', $plataforma->grupo_plataforma);
                                    $set('entidad_plataforma', $plataforma->entidad_plataforma);
                                }
                            }),

                        TextInput::make('view_plataforma')
                            ->label('Plataforma')
                            ->columnSpan(12)
                            ->visibleOn('view')
                            ->disabled()
                            ->dehydrated(false)
                            ->prefixIcon('heroicon-m-computer-desktop')
                            ->formatStateUsing(fn (?Model $record) => $record?->plataforma?->nombre_plataforma ?? '-'),

                        RichEditor::make('descripcion')
                            ->label('Descripción')
                            ->columnSpan(8)
                            ->toolbarButtons([
                                'bold', 'italic', 'underline',
                                'alignStart', 'alignCenter', 'alignEnd',
                                'undo', 'redo',
                            ])
                            ->disabledOn('view'),

                        Grid::make(2)
                            ->columnSpan(4)
                            ->schema([
                                Select::make('id_visibilidad')
                                    ->label('Visibilidad')
                                    ->options(Visibilidad::query()->where('estado_visibilidad', 1)->pluck('tipo_visibilidad', 'id_visibilidad'))
                                    ->searchable()
                                    ->required()
                                    ->hiddenOn('view'),

                                TextInput::make('view_visibilidad')
                                    ->label('Visibilidad')
                                    ->visibleOn('view')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->formatStateUsing(fn (?Model $record) => $record?->visibilidad?->tipo_visibilidad ?? '-')
                                    ->prefixIcon(function (?Model $record) {
                                        $tipo = $record?->visibilidad?->tipo_visibilidad;
                                        if (!$tipo) return 'heroicon-m-question-mark-circle';

                                        return in_array($tipo, ['Publico', 'Público'])
                                            ? 'heroicon-m-eye'
                                            : 'heroicon-m-eye-slash';
                                    })
                                    ->extraInputAttributes(function (?Model $record) {
                                        $tipo = $record?->visibilidad?->tipo_visibilidad;
                                        if (!$tipo) return ['class' => 'text-gray-500'];

                                        return in_array($tipo, ['Publico', 'Público'])
                                            ? ['class' => 'text-green-600 font-bold']
                                            : ['class' => 'text-red-600 font-bold'];
                                    }),

                                Toggle::make('verificacion')
                                    ->label('Cuenta verificada')
                                    ->inline(false)
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->default(false)
                                    ->hiddenOn('view'),

                                TextInput::make('view_verificacion')
                                    ->label('Verificación')
                                    ->visibleOn('view')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->formatStateUsing(fn (?Model $record) => $record?->verificacion ? 'Verificada' : 'No verificada')
                                    ->prefixIcon(fn (?Model $record) =>
                                        $record?->verificacion ? 'heroicon-m-shield-check' : 'heroicon-m-shield-exclamation'
                                    )
                                    ->extraInputAttributes(fn (?Model $record) =>
                                        $record?->verificacion
                                            ? ['class' => 'text-blue-600 font-bold']
                                            : ['class' => 'text-gray-500']
                                    ),
                            ]),
                    ]),

                Section::make('Credenciales de acceso')
                    ->description('Datos asociados al usuario de la cuenta')
                    ->columns(4)
                    ->columnSpan(12)
                    ->schema([

                        TextInput::make('cuenta_usuario.username_cuenta')
                            ->label('Usuario')
                            ->maxLength(100)
                            ->formatStateUsing(fn ($state, ?Model $record) => $state ?? $record?->cuentaUsuario?->username_cuenta),

                        TextInput::make('cuenta_usuario.email_cuenta')
                            ->label('Correo electrónico')
                            ->email()
                            ->maxLength(150)
                            ->formatStateUsing(fn ($state, ?Model $record) => $state ?? $record?->cuentaUsuario?->email_cuenta),

                        TextInput::make('cuenta_usuario.number_cuenta')
                            ->label('Número')
                            ->maxLength(50)
                            ->formatStateUsing(fn ($state, ?Model $record) => $state ?? $record?->cuentaUsuario?->number_cuenta),

                        TextInput::make('cuenta_usuario.password_cuenta')
                            ->label('Contraseña')
                            ->required(fn (string $context) => $context === 'create')
                            ->password()
                            ->revealable()
                            ->hiddenOn('view')
                            ->disabled(fn (string $context) =>
                                $context === 'edit' &&
                                ! session()->get('cuenta_validada_' . request()->route('record'), false)
                            )
                            ->dehydrated(fn (string $context, $state) => $context === 'create' || (!empty($state) && $state !== '********'))
                            ->afterStateHydrated(function ($state, callable $set) {
                                $recordId = request()->route('record');
                                if ($recordId && session()->get("cuenta_validada_{$recordId}", false)) {
                                    $cuentaUsuario = \App\Models\CuentaUsuario::where('id_cuenta', $recordId)->first();
                                    if ($cuentaUsuario && $cuentaUsuario->password_cuenta) {
                                        try {
                                            $decrypted = Crypt::decryptString($cuentaUsuario->password_cuenta);
                                            $set('cuenta_usuario.password_cuenta', $decrypted);
                                        } catch (\Exception $e) {
                                            $set('cuenta_usuario.password_cuenta', null);
                                        }
                                    }
                                }
                            }),

                        TextInput::make('view_password')
                            ->label('Contraseña')
                            ->visibleOn('view')
                            ->disabled()
                            ->dehydrated(false)
                            ->prefixIcon('heroicon-m-lock-closed')
                            ->formatStateUsing(fn ($state) => $state === '********' || empty($state) ? '********' : $state),
                    ]),

            ]);
    }
}
