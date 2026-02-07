<?php

namespace App\Filament\Resources\Tarjetas\Pages;

use App\Filament\Resources\Tarjetas\TarjetaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTarjeta extends EditRecord
{
    protected static string $resource = TarjetaResource::class;

    public $montoTemporal = null;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['monto_tarjeta'] = $this->record->monto?->monto_tarjeta;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->montoTemporal = $data['monto_tarjeta'];

        unset($data['monto_tarjeta']);

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->monto()->updateOrCreate(
            ['id_tarjeta' => $this->record->id_tarjeta],
            ['monto_tarjeta' => $this->montoTemporal]
        );
    }

    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index');
    }
}
