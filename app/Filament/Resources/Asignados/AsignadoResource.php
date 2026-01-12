<?php

namespace App\Filament\Resources\Asignados;

use App\Filament\Resources\Asignados\Pages\CreateAsignado;
use App\Filament\Resources\Asignados\Pages\EditAsignado;
use App\Filament\Resources\Asignados\Pages\ListAsignados;
use App\Filament\Resources\Asignados\Schemas\AsignadoForm;
use App\Filament\Resources\Asignados\Tables\AsignadosTable;
use App\Models\Asignado;
use Illuminate\Support\Facades\Auth;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AsignadoResource extends Resource
{
    protected static ?string $model = Asignado::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Solicitudes';
    protected static UnitEnum|string|null $navigationGroup = 'Modulos';
    protected static ?int $navigationSort = 3;
    protected static ?string $recordTitleAttribute = 'solicitudes';

    public static function form(Schema $schema): Schema
    {
        return AsignadoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AsignadosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

   public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('id_usuario', Auth::id())->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAsignados::route('/'),
            'create' => CreateAsignado::route('/create'),
            'edit' => EditAsignado::route('/{record}/edit'),
        ];
    }
}
