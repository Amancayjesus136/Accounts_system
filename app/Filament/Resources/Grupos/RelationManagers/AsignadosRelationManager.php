<?php

namespace App\Filament\Resources\Grupos\RelationManagers;

use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema; // Importamos Schema
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class AsignadosRelationManager extends RelationManager
{
    protected static string $relationship = 'asignados';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('id_usuario')
                    ->label('Usuario')
                    ->columnSpanFull()
                    ->columns(1)
                    ->options(User::all()->pluck('email', 'id'))
                    ->searchable()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id_usuario')
            ->columns([
                TextColumn::make('usuario.name')
                    ->label('Nombre de integrante'),

                TextColumn::make('usuario.email')
                    ->label('Correo'),

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
            ->actions([
                EditAction::make()
                    ->modalWidth('md')
                    ->mutateFormDataUsing(function (array $data): array {
                        return $this->mutateFormDataBeforeCreate($data);
                    }),

                DeleteAction::make(),
            ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = $this->setEstadoPorDefecto($data);
        return $data;
    }

    private function setEstadoPorDefecto(array $data): array
    {
        $data['estado_asignado'] = 2;
        return $data;
    }
}
