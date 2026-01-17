<?php

namespace App\Filament\Resources\Cuentas\Pages;

use App\Filament\Resources\Cuentas\CuentaResource;
use App\Models\CuentaUsuario;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class CreateCuenta extends CreateRecord
{
    protected static string $resource = CuentaResource::class;
    protected array $cuentaUsuarioData = [];

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Cuenta creada correctamente';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->cuentaUsuarioData = $data['cuenta_usuario'] ?? [];
        unset($data['cuenta_usuario']);

        $data['estado_cuenta'] = 1;
        $data['id_usuario'] = Auth::id();
        return $data;
    }

    protected function afterCreate(): void
    {
        CuentaUsuario::create([
            'id_cuenta'       => $this->record->id_cuenta,
            'username_cuenta' => $this->cuentaUsuarioData['username_cuenta'] ?? null,
            'email_cuenta'    => $this->cuentaUsuarioData['email_cuenta'] ?? null,
            'number_cuenta'   => $this->cuentaUsuarioData['number_cuenta'] ?? null,
            'password_cuenta' => isset($this->cuentaUsuarioData['password_cuenta'])
                ? Crypt::encryptString($this->cuentaUsuarioData['password_cuenta'])
                : null,
            'estado_cuenta'   => 1,
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index');
    }
}
