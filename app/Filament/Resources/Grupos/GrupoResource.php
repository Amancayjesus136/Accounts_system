<?php

namespace App\Filament\Resources\Grupos;

use App\Filament\Resources\Grupos\Pages\CreateGrupo;
use App\Filament\Resources\Grupos\Pages\EditGrupo;
use App\Filament\Resources\Grupos\Pages\GruposChart;
use App\Filament\Resources\Grupos\Pages\GruposEstadisticas;
use App\Filament\Resources\Grupos\Pages\ListGrupos;
use App\Filament\Resources\Grupos\Pages\MisGrupos;
use App\Filament\Resources\Grupos\Schemas\GrupoForm;
use App\Filament\Resources\Grupos\Tables\GruposTable;
use App\Filament\Resources\Grupos\RelationManagers\AsignadosRelationManager;
use App\Models\Grupo;
use BackedEnum;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Pages\Page;
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
    protected static UnitEnum|string|null $navigationGroup = 'Gestión Operativa';
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Start;

    protected static ?string $recordTitleAttribute = 'grupos';

    public static function getRecordRouteKeyName(): string
    {
        return 'id_grupo';
    }

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
            AsignadosRelationManager::class,
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            ListGrupos::class,
            MisGrupos::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGrupos::route('/'),
            'mis-grupos' => MisGrupos::route('/mis-grupos'),
            'edit' => EditGrupo::route('/{record}/edit'),
            'estadisticas' => GruposEstadisticas::route('/{record}/estadisticas'),
        ];
    }
}
