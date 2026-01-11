<?php

namespace App\Filament\Resources\Visibilidads\Pages;

use App\Filament\Resources\Visibilidads\VisibilidadResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVisibilidad extends CreateRecord
{
    protected static string $resource = VisibilidadResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = $this->setEstadoPorDefecto($data);
        return $data;
    }

    private function setEstadoPorDefecto(array $data): array
    {
        $data['estado_visibilidad'] = 1;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index');
    }
}
