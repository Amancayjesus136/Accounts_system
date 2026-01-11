<?php

namespace App\Filament\Resources\Grupos;

use App\Filament\Resources\Grupos\Pages\CreateGrupo;
use App\Filament\Resources\Grupos\Pages\EditGrupo;
use App\Filament\Resources\Grupos\Pages\ListGrupos;
use App\Filament\Resources\Grupos\Schemas\GrupoForm;
use App\Filament\Resources\Grupos\Tables\GruposTable;
use App\Models\Grupo;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GrupoResource extends Resource
{
    protected static ?string $model = Grupo::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 2;
    protected static UnitEnum|string|null $navigationGroup = 'Modulos';

    protected static ?string $recordTitleAttribute = 'grupos';

    public static function form(Schema $schema): Schema
    {
        return GrupoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GruposTable::configure($table);
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
            'index' => ListGrupos::route('/'),
            // 'create' => CreateGrupo::route('/create'),
            'edit' => EditGrupo::route('/{record}/edit'),
        ];
    }
}
