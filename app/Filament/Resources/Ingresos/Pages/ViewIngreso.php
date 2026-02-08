<?php

namespace App\Filament\Resources\Ingresos\Pages;

use App\Filament\Resources\Ingresos\IngresoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewIngreso extends ViewRecord
{
    protected static string $resource = IngresoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
