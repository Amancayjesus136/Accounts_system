<?php

namespace App\Filament\Resources\Tarjetas;

use App\Filament\Resources\Tarjetas\Pages\CreateTarjeta;
use App\Filament\Resources\Tarjetas\Pages\EditTarjeta;
use App\Filament\Resources\Tarjetas\Pages\ListTarjetas;
use App\Filament\Resources\Tarjetas\Pages\ViewTarjeta;
use App\Filament\Resources\Tarjetas\Schemas\TarjetaForm;
use App\Filament\Resources\Tarjetas\Schemas\TarjetaInfolist;
use App\Filament\Resources\Tarjetas\Tables\TarjetasTable;
use App\Models\Tarjeta;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TarjetaResource extends Resource
{
    protected static ?string $model = Tarjeta::class;

    protected static string|BackedEnum|null $navigationIcon = 'bi-credit-card-2-front-fill';
    protected static UnitEnum|string|null $navigationGroup = 'Gestión Financiera';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'tarjetas';

    public static function form(Schema $schema): Schema
    {
        return TarjetaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TarjetaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TarjetasTable::configure($table);
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
            'index' => ListTarjetas::route('/'),
            // 'create' => CreateTarjeta::route('/create'),
            'view' => ViewTarjeta::route('/{record}'),
            'edit' => EditTarjeta::route('/{record}/edit'),
        ];
    }
}
