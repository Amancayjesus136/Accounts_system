<?php

namespace App\Filament\Resources\Tarjetas\Pages;

use App\Filament\Resources\Tarjetas\TarjetaResource;
use App\Models\Monto;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateTarjeta extends CreateRecord
{
    protected static string $resource = TarjetaResource::class;

    protected ?float $montoCapturado = null;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Tarjeta registrada correctamente';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->montoCapturado = $data['monto_tarjeta'] ?? 0;

        unset($data['monto_tarjeta']);

        $data['id_usuario'] = Auth::id();
        $data['estado_tarjeta'] = 1;

        return $data;
    }

    protected function afterCreate(): void
    {
        Monto::create([
            'id_tarjeta'     => $this->record->id_tarjeta,
            'monto_tarjeta'  => $this->montoCapturado,
            'estado_tarjeta' => 1,
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index');
    }
}
