<?php

namespace App\Filament\Resources\Presupuestos\Pages;

use App\Filament\Resources\Presupuestos\PresupuestoResource;
use App\Models\Categoria;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

class ListPresupuestos extends ListRecords
{
    protected static string $resource = PresupuestoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nuevo presupuesto')
                ->icon('heroicon-o-plus')
                ->modalHeading('Registrar presupuesto')
                ->modalSubmitActionLabel('Guardar presupuesto')
                ->modalWidth('2xl')
                ->successNotificationTitle('El presupuesto se registró correctamente')

                ->form([
                    Grid::make(2)
                        ->schema([

                            TextInput::make('monto_presupuesto')
                                ->label('Monto')
                                ->numeric()
                                ->prefix('S/')
                                ->placeholder('0.00')
                                ->required()
                                ->columnSpan(1),

                            Select::make('id_categoria')
                                ->label('Categoría')
                                ->searchable()
                                ->allowHtml()
                                ->options(function () {
                                    return Categoria::where('id_usuario', Auth::id())
                                        ->where('tipo_categoria', 'Gasto')
                                        ->get()
                                        ->mapWithKeys(function ($categoria) {

                                            $html = Blade::render(<<<HTML
                                                <div style="display:flex;align-items:center;gap:12px;">
                                                    <div style="
                                                        width:25px;
                                                        height:25px;
                                                        display:flex;
                                                        align-items:center;
                                                        justify-content:center;
                                                        background-color:#1f2937;
                                                        border-radius:6px;
                                                        flex-shrink:0;
                                                    ">
                                                        <x-{$categoria->icono_categoria} class="w-4 h-4 text-primary-600"/>
                                                    </div>
                                                    <span class="font-medium">{$categoria->nombre_categoria}</span>
                                                </div>
                                            HTML);

                                            return [$categoria->id_categoria => $html];
                                        });
                                })
                                ->required()
                                ->columnSpan(1),
                        ]),
                ])

                ->mutateFormDataUsing(function (array $data) {

                    $data['estado_presupuesto'] = 1;
                    $data['id_usuario'] = Auth::id();

                    return $data;
                }),
        ];
    }
}
