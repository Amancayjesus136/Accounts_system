<?php

namespace App\Filament\Resources\Cuentas;

use App\Filament\Resources\Cuentas\Pages\CreateCuenta;
use App\Filament\Resources\Cuentas\Pages\EditCuenta;
use App\Filament\Resources\Cuentas\Pages\ListCuentas;
use App\Filament\Resources\Cuentas\Pages\ViewCuenta;
use App\Filament\Resources\Cuentas\Schemas\CuentaForm;
use App\Filament\Resources\Cuentas\Tables\CuentasTable;
use App\Models\Cuenta;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CuentaResource extends Resource
{
    protected static ?string $model = Cuenta::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $recordTitleAttribute = 'cuentas';
    protected static UnitEnum|string|null $navigationGroup = 'Gestión Operativa';
    protected static ?int $navigationSort = 5;
    public static function form(Schema $schema): Schema
    {
        return CuentaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CuentasTable::configure($table);
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
            'index' => ListCuentas::route('/'),
            'create' => CreateCuenta::route('/create'),
            'view' => ViewCuenta::route('/{record}'),
            'edit' => EditCuenta::route('/{record}/edit'),
        ];
    }
}
