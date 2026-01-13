<?php

namespace App\Filament\Clusters;

use BackedEnum;
use UnitEnum;
use Filament\Clusters\Cluster;
// CORRECCIÓN: Importar la ubicación exacta del ENUM
use Filament\Pages\Enums\SubNavigationPosition;

class GruposCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Gestión de Grupos';

    protected static string|UnitEnum|null $navigationGroup = 'Modulos';

    protected static ?int $navigationSort = 2;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Start;
}
