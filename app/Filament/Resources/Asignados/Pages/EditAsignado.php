<?php

namespace App\Filament\Resources\Asignados\Pages;

use App\Filament\Resources\Asignados\AsignadoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAsignado extends EditRecord
{
    protected static string $resource = AsignadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
