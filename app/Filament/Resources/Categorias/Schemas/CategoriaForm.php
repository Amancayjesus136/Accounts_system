<?php

namespace App\Filament\Resources\Categorias\Schemas;

use Filament\Forms\Form; // Importante
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Blade;

class CategoriaForm
{
    public static function configure($form)
    {
        return $form
            ->columns(12)
            ->schema([

                Section::make('Información principal')
                    ->columnSpan(8)
                    ->schema([

                        Grid::make(3)->schema([
                            TextInput::make('nombre_categoria')
                                ->label('Nombre')
                                ->required(),

                            Select::make('tipo_categoria')
                                ->label('Tipo')
                                ->required()
                                ->options([
                                    'Gasto'     => 'Gastos',
                                    'Ingresos'  => 'Ingresos',
                                ])
                                ->native(false),

                            Select::make('icono_categoria')
                                ->label('Selecciona un Icono')
                                ->searchable()
                                ->allowHtml()
                                ->options(self::getIconOptions())
                                ->required(),
                        ]),

                        Textarea::make('descripcion_categoria')
                            ->label('Descripción')
                            ->rows(3)
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Estado y Detalles')
                    ->columnSpan(4)
                    ->schema([

                        Placeholder::make('created_at')
                            ->label('Creado')
                            ->content(fn ($record): string => $record?->created_at?->diffForHumans() ?? '-'),

                        Placeholder::make('updated_at')
                            ->label('Última modificación')
                            ->content(fn ($record): string => $record?->updated_at?->diffForHumans() ?? '-'),
                    ]),

            ]);
    }

    public static function getIconOptions(): array
    {
        $grupos = [
            'Entretenimiento y Streaming' => [
                'mdi-netflix' => 'Netflix',
                'mdi-youtube' => 'YouTube',
                'mdi-movie-open' => 'HBO / Prime / Cine',
                'mdi-spotify' => 'Spotify / Música',
                'mdi-twitch' => 'Twitch / Stream',
                'mdi-gamepad-variant' => 'Videojuegos',
                'mdi-ticket' => 'Cine / Entradas',
            ],
            'Servicios Básicos' => [
                'mdi-water' => 'Agua Potable',
                'mdi-flash' => 'Luz / Electricidad',
                'mdi-wifi' => 'Internet / Wifi',
                'mdi-gas-cylinder' => 'Gas',
                'mdi-phone' => 'Teléfono',
                'mdi-trash-can' => 'Basura',
                'mdi-home-city' => 'Alquiler',
            ],
            'Mercado y Compras' => [
                'mdi-store' => 'Bodega / Tienda',
                'mdi-cart' => 'Supermercado',
                'mdi-basket' => 'Canasta Básica',
                'mdi-shopping' => 'Shopping / Ropa',
                'mdi-tshirt-crew' => 'Moda / Ropa',
                'mdi-shoe-sneaker' => 'Zapatos',
                'mdi-hanger' => 'Lavandería / Tintorería',
            ],
            'Mascotas' => [
                'mdi-paw' => 'Mascotas (General)',
                'mdi-dog' => 'Perro',
                'mdi-cat' => 'Gato',
                'mdi-bone' => 'Croquetas / Comida',
                'mdi-fish' => 'Acuario / Peces',
                'mdi-doctor' => 'Veterinaria',
            ],
            'Salud y Bienestar' => [
                'mdi-hospital-box' => 'Hospital / Clínica',
                'mdi-pill' => 'Farmacia / Medicamentos',
                'mdi-medical-bag' => 'Consultas Médicas',
                'mdi-tooth' => 'Dentista',
                'mdi-dumbbell' => 'Gimnasio / Deporte',
                'mdi-spa' => 'Spa / Barbería / Peluquería',
            ],
            'Transporte' => [
                'mdi-car' => 'Gasolina / Auto',
                'mdi-taxi' => 'Taxi / Uber / Didi',
                'mdi-bus' => 'Pasaje / Bus',
                'mdi-train' => 'Tren / Metro',
                'mdi-motorbike' => 'Moto',
                'mdi-bicycle' => 'Bicicleta',
                'mdi-tools' => 'Mecánico / Taller',
            ],
            'Comida y Bebidas' => [
                'mdi-food' => 'Comida (General)',
                'mdi-silverware-fork-knife' => 'Restaurante',
                'mdi-hamburger' => 'Fast Food',
                'mdi-pizza' => 'Pizza',
                'mdi-coffee' => 'Café',
                'mdi-cup-water' => 'Bebidas',
                'mdi-cake-variant' => 'Postres / Panadería',
                'mdi-beer' => 'Cerveza / Licores',
            ],
            'Educación y Otros' => [
                'mdi-school' => 'Colegio / Universidad',
                'mdi-book-open-page-variant' => 'Libros / Útiles',
                'mdi-bank' => 'Banco / Préstamos',
                'mdi-gift' => 'Regalos',
            ]
        ];

        $opcionesConIcono = [];

        foreach ($grupos as $tituloGrupo => $items) {
            foreach ($items as $icono => $texto) {
                $html = Blade::render(<<<HTML
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="
                            width: 32px;
                            height: 32px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            background-color: #e5e7eb;
                            border-radius: 6px;
                            flex-shrink: 0;
                        ">
                            <x-$icono style="width: 20px; height: 20px; color: #1f2937;" />
                        </div>
                        <!-- <span style="font-weight: 500; font-size: 14px;">$texto</span> -->
                    </div>
                HTML);

                $opcionesConIcono[$tituloGrupo][$icono] = $html;
            }
        }

        return $opcionesConIcono;
    }
}
