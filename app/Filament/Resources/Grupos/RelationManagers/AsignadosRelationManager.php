<?php

namespace App\Filament\Resources\Grupos\RelationManagers;

use App\Filament\Resources\Grupos\Pages\GruposEstadisticas;
use App\Models\User;
use App\Filament\Resources\Grupos\Widgets\CuentasPorIntegranteChart;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class AsignadosRelationManager extends RelationManager
{
    protected static string $relationship = 'asignados';

    // public function getTabs(): array
    // {
    //     return [
    //         'integrantes' => Tab::make('Integrantes')
    //             ->icon('heroicon-o-user-group')
    //             ->modifyQueryUsing(
    //                 fn (Builder $query) => $query->where('estado_asignado', 1)
    //             ),

    //         'pendientes' => Tab::make('Pendientes')
    //             ->icon('heroicon-o-clock')
    //             ->modifyQueryUsing(
    //                 fn (Builder $query) => $query->where('estado_asignado', 2)
    //             ),

    //         'rechazados' => Tab::make('Rechazados')
    //             ->icon('heroicon-o-x-circle')
    //             ->modifyQueryUsing(
    //                 fn (Builder $query) => $query->where('estado_asignado', 0)
    //             ),
    //     ];
    // }

    public function getHeaderWidgets(): array
    {
        if ($this->getActiveTab() !== 'estadisticas') {
            return [];
        }

        return [
            CuentasPorIntegranteChart::class,
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('id_usuario')
                ->label('Usuario')
                ->searchable()
                ->required()
                ->getSearchResultsUsing(function (string $search, RelationManager $livewire) {
                    $usuariosAsignados = $livewire->getOwnerRecord()
                        ->asignados()
                        ->pluck('id_usuario');

                    return User::where('email', 'like', "%{$search}%")
                        ->whereNotIn('id', $usuariosAsignados)
                        ->limit(50)
                        ->pluck('email', 'id');
                })
                ->getOptionLabelUsing(
                    fn ($value) => User::find($value)?->email
                ),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id_usuario')
            ->columns([
                TextColumn::make('usuario.name')
                    ->label('Nombre')
                    ->searchable(),

                TextColumn::make('usuario.email')
                    ->label('Correo')
                    ->searchable(),

                TextColumn::make('estado_asignado')
                    ->badge()
                    ->formatStateUsing(fn (int $state) => match ($state) {
                        1 => 'Integrante',
                        2 => 'Pendiente',
                        0 => 'Rechazado',
                    })
                    ->color(fn (int $state) => match ($state) {
                        1 => 'success',
                        2 => 'warning',
                        0 => 'danger',
                    }),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(
                        fn (array $data) => $this->mutateFormDataBeforeCreate($data)
                    ),

                Action::make('estadisticas')
                    ->label('Estadísticas')
                    ->icon('heroicon-o-chart-bar')
                    ->extraAttributes([
                        'class' => 'bg-yellow-500 text-white hover:bg-yellow-600 focus:ring-yellow-500 border-none [&>svg]:!text-white',
                        'style' => 'background-color: #16a34a !important; color: white !important;',
                    ])
                    ->url(fn () => GruposEstadisticas::getUrl([
                        'record' => $this->getOwnerRecord()->getKey(),
                    ])),

            ])

            ->actions([
                Action::make('verCuentas')
                    ->label('Ver cuentas')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading('Cuentas del usuario')
                    ->modalWidth('5xl')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar')

                    ->hidden(fn ($record) => $record->estado_asignado === 0)

                    ->infolist([
                        RepeatableEntry::make('cuentas_filtradas')
                            ->hiddenLabel()
                            ->state(function ($record) {
                                if ($record->estado_asignado === 1) {
                                    return $record->usuario?->cuentas;
                                }

                                return [];
                            })
                            ->placeholder('Este usuario tiene cuentas en proceso de aceptación')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        // TextEntry::make('plataforma.grupo_plataforma')
                                        //     ->label('Grupo')
                                        //     ->icon('heroicon-m-rectangle-group'),

                                        // TextEntry::make('plataforma.entidad_plataforma')
                                        //     ->label('Entidad')
                                        //     ->icon('heroicon-m-building-office'),

                                        TextEntry::make('plataforma.nombre_plataforma')
                                            ->label('Plataforma')
                                            ->weight('bold')
                                            ->icon('heroicon-m-computer-desktop'),

                                        TextEntry::make('visibilidad.tipo_visibilidad')
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

                                        // TextEntry::make('estado_cuenta')
                                        //     ->label('Estado')
                                        //     ->badge()
                                        //     ->formatStateUsing(fn ($state) => $state ? 'Activa' : 'Inactiva')
                                        //     ->color(fn ($state) => $state ? 'success' : 'danger')
                                        //     ->icon(fn ($state) => $state ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle'),

                                        TextEntry::make('verificacion')
                                            ->label('Verificación')
                                            ->badge()
                                            ->formatStateUsing(fn ($state) => $state ? 'Verificada' : 'No verificada')
                                            ->color(fn ($state) => $state ? 'info' : 'gray')
                                            ->icon(fn ($state) => $state ? 'heroicon-m-shield-check' : 'heroicon-m-shield-exclamation'),
                                    ]),
                            ])
                            ->columns(1),
                    ])
                // EditAction::make()
                //     ->modalWidth('md')
                //     ->mutateFormDataUsing(fn (array $data) => $this->mutateFormDataBeforeCreate($data)),

                // DeleteAction::make(),
            ])

            ->filters([
                SelectFilter::make('estado_asignado')
                    ->options([
                        1 => 'Integrante',
                        2 => 'Pendiente',
                        0 => 'Rechazado',
                    ]),
            ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['estado_asignado'] = 2;

        if ($usuario = User::find($data['id_usuario'])) {
            Notification::make()
                ->title('Asignación de Grupo')
                ->body('Has sido asignado a un nuevo grupo.')
                ->success()
                ->sendToDatabase($usuario);
        }

        return $data;
    }
}
