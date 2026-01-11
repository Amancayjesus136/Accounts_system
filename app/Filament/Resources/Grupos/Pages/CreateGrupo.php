<?php

namespace App\Filament\Resources\Grupos\Pages;

use App\Filament\Resources\Grupos\GrupoResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateGrupo extends CreateRecord
{
    protected static string $resource = GrupoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = $this->setIdUser($data);
        $data = $this->setEstadoPorDefecto($data);
        return $data;
    }

    protected function setIdUser(array $data): array
    {
        $data['id_user'] = Auth::id();

        return $data;
    }

    private function setEstadoPorDefecto(array $data): array
    {
        $data['estado_grupo'] = 1;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index');
    }
}
