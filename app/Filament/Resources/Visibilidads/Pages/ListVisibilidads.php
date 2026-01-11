<?php

namespace App\Filament\Resources\Visibilidads\Pages;

use App\Filament\Resources\Visibilidads\VisibilidadResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVisibilidads extends ListRecords
{
    protected static string $resource = VisibilidadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
