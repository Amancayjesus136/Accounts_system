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
use Illuminate\Database\Eloquent\Builder;

class CuentasUsuarioTable extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithActions;

    public Model $record;

    public function table(Table $table): Table
    {
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
