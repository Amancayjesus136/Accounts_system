<?php

namespace App\Filament\Resources\Tarjetas\Pages;

use App\Filament\Resources\Tarjetas\TarjetaResource;
use App\Models\Monto;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class ListTarjetas extends ListRecords
{
    protected static string $resource = TarjetaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nueva tarjeta')
                ->icon('heroicon-o-plus')
                ->modalHeading('Crear tarjeta')
                ->modalSubmitActionLabel('Guardar')
                ->modalWidth('5xl')
                ->successNotificationTitle('La tarjeta y su monto han sido creados correctamente')
                ->form([
                    Grid::make(3)
                        ->schema([
                            Select::make('tipo_tarjeta')
                                ->label('Tipo de método de pago')
                                ->options([
                                    'Tarjeta de Crédito' => 'Tarjeta de Crédito',
                                    'Tarjeta de Débito' => 'Tarjeta de Débito',
                                    'Efectivo' => 'Efectivo',
                                    'Yape' => 'Yape',
                                    'Plin' => 'Plin',
                                    'PayPal' => 'PayPal',
                                    'Cheque' => 'Cheque',
                                    'Vale' => 'Vale',
                                    'Cupón' => 'Cupón',
                                    'Gift Card' => 'Gift Card',
                                ])
                                ->required()
                                ->native(false),

                            TextInput::make('monto_tarjeta')
                                ->label('Monto Inicial')
                                ->required()
                                ->numeric()
                                ->prefix('S/.'),

                            TextInput::make('nombre_tarjeta')
                                ->label('Tipo de Cuenta')
                                ->required(),
                        ]),
                ])
                ->using(function (array $data, string $model): Model {

                    $montoValor = $data['monto_tarjeta'];
                    unset($data['monto_tarjeta']);

                    $data['estado_tarjeta'] = 1;
                    $data['id_usuario'] = Auth::id();

                    $tarjeta = $model::create($data);

                    Monto::create([
                        'id_tarjeta'     => $tarjeta->id_tarjeta,
                        'monto_tarjeta'  => $montoValor,
                        'estado_monto'   => 1,
                    ]);

                    return $tarjeta;
                }),
        ];
    }
}
