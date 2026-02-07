<?php

namespace App\Filament\Resources\Tarjetas\Pages;

use App\Filament\Resources\Tarjetas\TarjetaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTarjeta extends ViewRecord
{
    protected static string $resource = TarjetaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
