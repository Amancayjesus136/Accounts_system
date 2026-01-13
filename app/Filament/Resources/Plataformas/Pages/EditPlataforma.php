<?php

namespace App\Filament\Resources\Plataformas\Pages;

use App\Filament\Resources\Plataformas\PlataformaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPlataforma extends EditRecord
{
    protected static string $resource = PlataformaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
