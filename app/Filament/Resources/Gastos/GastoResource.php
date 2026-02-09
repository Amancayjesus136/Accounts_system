<?php

namespace App\Filament\Resources\Gastos;

use App\Filament\Resources\Gastos\Pages\CreateGasto;
use App\Filament\Resources\Gastos\Pages\EditGasto;
use App\Filament\Resources\Gastos\Pages\ListGastos;
use App\Filament\Resources\Gastos\Pages\ViewGasto;
use App\Filament\Resources\Gastos\Schemas\GastoForm;
use App\Filament\Resources\Gastos\Schemas\GastoInfolist;
use App\Filament\Resources\Gastos\Tables\GastosTable;
use App\Models\Gasto;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class GastoResource extends Resource
{
    protected static ?string $model = Gasto::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-currency-dollar';
    protected static UnitEnum|string|null $navigationGroup = 'Gestión Financiera';
    protected static ?int $navigationSort = 3;
    protected static ?string $recordTitleAttribute = 'gastos';

    public static function form(Schema $schema): Schema
    {
        return GastoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return GastoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GastosTable::configure($table);
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
            'index' => ListGastos::route('/'),
            // 'create' => CreateGasto::route('/create'),
            'view' => ViewGasto::route('/{record}'),
            'edit' => EditGasto::route('/{record}/edit'),
        ];
    }
}
