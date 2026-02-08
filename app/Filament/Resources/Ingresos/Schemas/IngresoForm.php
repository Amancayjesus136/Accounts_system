<?php

namespace App\Filament\Resources\Ingresos\Schemas;

use App\Models\Categoria;
use App\Models\Tarjeta;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea; // Usaré Textarea para descripción, es mejor para notas
use Filament\Forms\Form;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Blade;

class IngresoForm
{
    public static function configure($form)
    {
        return $form
            ->columns(12)
            ->schema([

                Section::make('Información del Ingreso')
                    ->columns(3)
                    ->columnSpan(8)
                    ->schema([

                        TextInput::make('monto')
                            ->label('Monto')
                            ->numeric()
                            ->prefix('S/')
                            ->placeholder('0.00')
                            ->required(),

                        Select::make('id_tarjeta')
                            ->label('Tarjeta / Cuenta')
                            ->options(Tarjeta::all()->pluck('nombre_tarjeta', 'id_tarjeta'))
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('id_categoria')
                            ->label('Categoría')
                            ->searchable()
                            ->allowHtml()
                            ->options(self::getCategoriasOptions())
                            ->required(),

                        RichEditor::make('descripcion')
                            ->label('Descripción')
                            ->toolbarButtons([
                                'bold', 'italic', 'underline',
                                'alignStart', 'alignCenter', 'alignEnd',
                                'undo', 'redo',
                            ])
                            ->columnSpanFull(),
                    ]),

                Section::make('Detalles del Registro')
                    ->columns(1)
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

    public static function getCategoriasOptions(): array
    {
        return Categoria::all()->mapWithKeys(function ($categoria) {

            $html = Blade::render(<<<HTML
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="
                        width: 25px;
                        height: 25px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        background-color: #1f2937;
                        border-radius: 6px;
                        flex-shrink: 0;
                    ">
                        <x-{$categoria->icono_categoria} class="w-5 h-5 text-primary-500"/>
                    </div>
                    <span style="font-weight: 500;">{$categoria->nombre_categoria}</span>
                </div>
            HTML);

            return [$categoria->id_categoria => $html];
        })->toArray();
    }
}
