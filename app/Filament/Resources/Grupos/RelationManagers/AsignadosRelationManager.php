<?php

namespace App\Filament\Resources\Grupos\RelationManagers;

use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables; // Importante para acceder a Tables\Actions
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AsignadosRelationManager extends RelationManager
{
    protected static string $relationship = 'asignados';

    // public function getTabs(): array
    // {
    //     return [
    //         // 'todos' => Tab::make('Todos')
    //         //     ->badge($this->getOwnerRecord()->asignados()->count()),

    //         'integrantes' => Tab::make('Integrantes')
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('estado_asignado', 1))
    //             ->icon('heroicon-m-user-group')
    //             ->badge($this->getOwnerRecord()->asignados()->where('estado_asignado', 1)->count())
    //             ->badgeColor('success'),

    //         'pendientes' => Tab::make('Pendientes')
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('estado_asignado', 2))
    //             ->icon('heroicon-m-clock')
    //             ->badge($this->getOwnerRecord()->asignados()->where('estado_asignado', 2)->count())
    //             ->badgeColor('warning'),

    //         'rechazados' => Tab::make('Rechazados')
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('estado_asignado', 0))
    //             ->icon('heroicon-m-x-circle')
    //             ->badge($this->getOwnerRecord()->asignados()->where('estado_asignado', 0)->count())
    //             ->badgeColor('danger'),
    //     ];
    // }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('id_usuario')
                    ->label('Usuario')
                    ->columnSpanFull()
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
                    ->getOptionLabelUsing(fn ($value): ?string => User::find($value)?->email),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id_usuario')
            ->columns([
                TextColumn::make('usuario.name')->label('Nombre de integrante')->searchable(),
                TextColumn::make('usuario.email')->label('Correo')->searchable(),
                TextColumn::make('estado_asignado')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => 'Integrante',
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
                        1 => 'heroicon-o-user',
                        2 => 'heroicon-o-clock',
                        0 => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->modalWidth('md')
                    ->mutateFormDataUsing(function (array $data): array {
                        return $this->mutateFormDataBeforeCreate($data);
                    }),
            ])
            ->filters([
                SelectFilter::make('estado_asignado')
                    ->label('Estado')
                    ->options([
                        1 => 'Integrante',
                        2 => 'Pendiente',
                        0 => 'Rechazado',
                    ]),
            ])
            ->actions([
                // EditAction::make()
                //     ->modalWidth('md')
                //     ->mutateFormDataUsing(function (array $data): array {
                //         return $this->mutateFormDataBeforeCreate($data);
                //     }),
                // DeleteAction::make(),
            ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = $this->setEstadoPorDefecto($data);

        $usuarioDestino = User::find($data['id_usuario']);

        if ($usuarioDestino) {
            Notification::make()
                ->title('Asignación de Grupo')
                ->body("Has sido asignado a un nuevo grupo.")
                ->icon('heroicon-o-user-group')
                ->success()
                ->sendToDatabase($usuarioDestino);
        }

        return $data;
    }

    private function setEstadoPorDefecto(array $data): array
    {
        $data['estado_asignado'] = 2;
        return $data;
    }
}
