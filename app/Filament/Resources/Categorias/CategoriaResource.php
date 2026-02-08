<?php

namespace App\Filament\Resources\Categorias;

use App\Filament\Resources\Categorias\Pages\CreateCategoria;
use App\Filament\Resources\Categorias\Pages\EditCategoria;
use App\Filament\Resources\Categorias\Pages\ListCategorias;
use App\Filament\Resources\Categorias\Schemas\CategoriaForm;
use App\Filament\Resources\Categorias\Tables\CategoriasTable;
use App\Models\Categoria;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CategoriaResource extends Resource
{
    protected static ?string $model = Categoria::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-queue-list';
    protected static ?int $navigationSort = 3;
    protected static UnitEnum|string|null $navigationGroup = 'Gestión Financiera';
    protected static ?string $recordTitleAttribute = 'categorias';

    public static function form(Schema $schema): Schema
    {
        return CategoriaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoriasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategorias::route('/'),
            // 'create' => CreateCategoria::route('/create'),
            'edit' => EditCategoria::route('/{record}/edit'),
        ];
    }
}
