<?php

namespace App\Filament\Resources\Categorias\Pages;

use App\Filament\Resources\Categorias\CategoriaResource;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Grid;
use Guava\IconPicker\Forms\Components\IconPicker;
use Illuminate\Support\Facades\Blade;

class ListCategorias extends ListRecords
{
    protected static string $resource = CategoriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nueva categoría')
                ->icon('heroicon-o-plus')
                ->modalHeading('Crear categoría')
                ->modalSubmitActionLabel('Guardar')
                ->modalWidth('3xl')
                ->successNotificationTitle('La categoría ha sido creada correctamente')
                ->form([
                    Grid::make(3)
                        ->schema([
                            TextInput::make('nombre_categoria')
                                ->label('Nombre')
                                ->required()
                                ->columnSpan(1),

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
                            ->options(function () {
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
                                            </div>
                                        HTML);

                                        $opcionesConIcono[$tituloGrupo][$icono] = $html;
                                    }
                                }

                                return $opcionesConIcono;
                            })
                            ->required(),
                        ]),

                    TextInput::make('descripcion_categoria')
                        ->label('Descripción')
                        ->required()
                        ->columnSpanFull(),
                ])
                ->mutateFormDataUsing(function (array $data) {
                    $data['estado_categoria'] = 1;
                    return $data;
                }),
        ];
    }
}
