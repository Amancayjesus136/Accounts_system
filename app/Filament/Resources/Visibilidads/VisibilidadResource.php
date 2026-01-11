<?php

namespace App\Filament\Resources\Visibilidads;

use App\Filament\Resources\Visibilidads\Pages\CreateVisibilidad;
use App\Filament\Resources\Visibilidads\Pages\EditVisibilidad;
use App\Filament\Resources\Visibilidads\Pages\ListVisibilidads;
use App\Filament\Resources\Visibilidads\Schemas\VisibilidadForm;
use App\Filament\Resources\Visibilidads\Tables\VisibilidadsTable;
use App\Models\Visibilidad;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class VisibilidadResource extends Resource
{
    protected static ?string $model = Visibilidad::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-eye';
    protected static UnitEnum|string|null $navigationGroup = 'Configuraciones';
    protected static ?string $navigationLabel = 'Visibilidad';
    protected static ?int $navigationSort = 3;
    protected static ?string $pluralModelLabel = 'visibilidades';
    protected static ?string $recordTitleAttribute = 'visibilidad';

    public static function form(Schema $schema): Schema
    {
        return VisibilidadForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VisibilidadsTable::configure($table);
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
            'index' => ListVisibilidads::route('/'),
            // 'create' => CreateVisibilidad::route('/create'),
            'edit' => EditVisibilidad::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'tipo_visibilidad',
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->tipo_visibilidad;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Estado' => $record->estado_visibilidad === 1 ? 'Activo' : 'Inactivo',
        ];
    }
}
