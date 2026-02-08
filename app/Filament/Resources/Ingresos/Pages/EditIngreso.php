<?php

namespace App\Filament\Resources\Ingresos\Pages;

use App\Filament\Resources\Ingresos\IngresoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditIngreso extends EditRecord
{
    protected static string $resource = IngresoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
