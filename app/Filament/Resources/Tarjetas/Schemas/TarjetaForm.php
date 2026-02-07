<?php

namespace App\Filament\Resources\Tarjetas\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TarjetaForm
{
    public static function configure($form)
    {
        return $form
            ->columns(12)
            ->schema([

                Section::make('Información principal')
                    ->columns(3)
                    ->columnSpan(8)
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
                            ->required()
                            ->numeric(),

                        TextInput::make('nombre_tarjeta')
                            ->label('Tipo de Cuenta')
                            ->required(),
                    ]),

                Section::make('Detalles adicionales')
                    ->columns(1)
                    ->columnSpan(4)
                    ->schema([

                        Placeholder::make('created_at')
                            ->label('Creado')
                            ->content(fn ($record): string => $record?->created_ago ?? '-'),

                        Placeholder::make('updated_at')
                            ->label('Última modificación')
                            ->content(fn ($record): string => $record?->updated_ago ?? '-'),
                    ]),

            ]);
    }
}
