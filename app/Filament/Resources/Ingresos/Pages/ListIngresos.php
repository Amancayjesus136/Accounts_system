<?php

namespace App\Filament\Resources\Ingresos\Pages;

use App\Filament\Resources\Ingresos\IngresoResource;
use App\Models\Categoria;
use App\Models\Tarjeta;
use App\Models\Ingreso;
use App\Models\Monto;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

class ListIngresos extends ListRecords
{
    protected static string $resource = IngresoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nuevo ingreso')
                ->icon('heroicon-o-plus')
                ->modalHeading('Registrar Ingreso')
                ->modalSubmitActionLabel('Guardar Ingreso')
                ->modalWidth('2xl')
                ->successNotificationTitle('El ingreso se registró correctamente')

                ->form([
                    Grid::make(3)
                        ->schema([

                            TextInput::make('monto')
                                ->label('Monto')
                                ->numeric()
                                ->prefix('S/')
                                ->placeholder('0.00')
                                ->required()
                                ->columnSpan(1),

                           Select::make('id_tarjeta')
                                ->label('Tarjeta')
                                ->options(fn () => Tarjeta::where('id_usuario', Auth::id())->pluck('tipo_tarjeta', 'id_tarjeta'))
                                ->searchable()
                                ->preload()
                                ->required()
                                ->columnSpan(1),

                            Select::make('id_categoria')
                                ->label('Categoría')
                                ->searchable()
                                ->allowHtml()
                                ->options(function () {
                                    return Categoria::where('id_usuario', Auth::id())
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

                            RichEditor::make('descripcion')
                                ->label('Descripción')
                                ->toolbarButtons([
                                    'bold',
                                    'italic',
                                    'underline',
                                    'alignStart',
                                    'alignCenter',
                                    'alignEnd',
                                    'undo',
                                    'redo',
                                ])
                                ->columnSpanFull(),
                        ]),
                ])

                ->mutateFormDataUsing(function (array $data) {

                    $data['estado_ingreso'] = 1;
                    $data['id_usuario'] = Auth::id();

                    return $data;
                })

                ->after(function (Ingreso $record) {

                    $tarjeta = Tarjeta::find($record->id_tarjeta);

                    if ($tarjeta) {

                        $saldoActual = $tarjeta->ultimoMonto
                            ? $tarjeta->ultimoMonto->monto_tarjeta
                            : 0;

                        $nuevoSaldo = $saldoActual + $record->monto;

                        Monto::create([
                            'id_tarjeta'    => $tarjeta->id_tarjeta,
                            'monto_tarjeta' => $nuevoSaldo,
                            'estado_monto'  => 1,
                        ]);
                    }
                }),
        ];
    }
}
