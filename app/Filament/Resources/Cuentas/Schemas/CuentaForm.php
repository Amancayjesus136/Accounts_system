<?php

namespace App\Filament\Resources\Cuentas\Schemas;

use App\Models\Plataforma;
use Filament\Forms\Components\Select;
use Filament\Forms\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;

class CuentaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->schema([

                Section::make('Información principal')
                    ->columns(4)
                    ->columnSpan(9)
                    ->schema([

                        Select::make('grupo_plataforma')
                            ->label('Grupo Plataforma')
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
                            ->label('Entidad Plataforma')
                            ->options(function (callable $get) {
                                $grupo = $get('grupo_plataforma');

                                if (!$grupo) {
                                    return [];
                                }

                                return Plataforma::query()
                                    ->where('grupo_plataforma', $grupo)
                                    ->distinct()
                                    ->pluck('entidad_plataforma', 'entidad_plataforma');
                            })
                            ->reactive()
                            ->required()
                            ->afterStateUpdated(fn (callable $set) => $set('id_plataforma', null)),

                        Select::make('id_plataforma')
                            ->label('Plataforma')
                            ->options(function (callable $get) {
                                $grupo   = $get('grupo_plataforma');
                                $entidad = $get('entidad_plataforma');

                                if (!$grupo || !$entidad) {
                                    return [];
                                }

                                return Plataforma::query()
                                    ->where('grupo_plataforma', $grupo)
                                    ->where('entidad_plataforma', $entidad)
                                    ->pluck('nombre_plataforma', 'id_plataforma');
                            })
                            ->searchable()
                            ->required(),

                        Toggle::make('verificada')
                            ->label('Cuenta Verificada')
                            ->onIcon('heroicon-o-check-badge')
                            ->offIcon('heroicon-o-x-circle')
                            ->onColor('success')
                            ->offColor('danger')
                            ->default(false),

                        RichEditor::make('descripcion')
                            ->columnSpanFull()
                            ->toolbarButtons([
                                ['bold', 'italic', 'underline'],
                                ['alignStart', 'alignCenter', 'alignEnd'],
                                ['undo', 'redo'],
                            ])
                            ->extraAttributes(['style' => 'min-height: 15em;']),

                    ]),

                Section::make('Información principal')
                    ->columns(1)
                    ->columnSpan(3)
                    ->schema([

                        Toggle::make('verificada')
                            ->label('Cuenta Verificada')
                            ->onIcon('heroicon-o-check-badge')
                            ->offIcon('heroicon-o-x-circle')
                            ->onColor('success')
                            ->offColor('danger')
                            ->default(false),

                    ]),
            ]);
    }
}
