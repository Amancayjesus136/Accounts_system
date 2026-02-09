<?php

namespace App\Filament\Resources\Ingresos;

use App\Filament\Resources\Ingresos\Pages\CreateIngreso;
use App\Filament\Resources\Ingresos\Pages\EditIngreso;
use App\Filament\Resources\Ingresos\Pages\ListIngresos;
use App\Filament\Resources\Ingresos\Pages\ViewIngreso;
use App\Filament\Resources\Ingresos\Schemas\IngresoForm;
use App\Filament\Resources\Ingresos\Schemas\IngresoInfolist;
use App\Filament\Resources\Ingresos\Tables\IngresosTable;
use App\Models\Ingreso;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class IngresoResource extends Resource
{
    protected static ?string $model = Ingreso::class;

    protected static string|BackedEnum|null $navigationIcon = 'fas-money-bill-trend-up';
    protected static UnitEnum|string|null $navigationGroup = 'Gestión Financiera';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'ingresos';

    public static function form(Schema $schema): Schema
    {
        return IngresoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return IngresoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IngresosTable::configure($table);
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
            'index' => ListIngresos::route('/'),
            // 'create' => CreateIngreso::route('/create'),
            'view' => ViewIngreso::route('/{record}'),
            'edit' => EditIngreso::route('/{record}/edit'),
        ];
    }
}
