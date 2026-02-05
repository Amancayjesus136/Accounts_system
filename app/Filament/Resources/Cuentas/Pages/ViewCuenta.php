<?php

namespace App\Filament\Resources\Cuentas\Pages;

use App\Filament\Resources\Cuentas\CuentaResource;
use App\Models\Token;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ViewCuenta extends ViewRecord
{
    protected static string $resource = CuentaResource::class;

    public bool $codigoValidado = false;
    protected int $minutosExpiracion = 1;

    public function mount(int|string $record): void
    {
        parent::mount($record);
        $this->verificarExpiracion();
    }

    protected function verificarExpiracion(): void
    {
        $id = $this->record->id_cuenta;
        $horaValidacion = session()->get("cuenta_hora_{$id}");

        if ($horaValidacion) {
            if (Carbon::parse($horaValidacion)->addMinutes($this->minutosExpiracion)->isPast()) {
                session()->forget(["cuenta_validada_{$id}", "cuenta_hora_{$id}"]);
                $this->codigoValidado = false;
            } else {
                $this->codigoValidado = session()->get("cuenta_validada_{$id}", false);
            }
        } else {
            $this->codigoValidado = false;
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->verificarExpiracion();

        if ($this->record->cuentausuario) {
            $passCifrado = $this->record->cuentausuario->password_cuenta;

            $data['cuenta_usuario'] = [
                'username_cuenta' => $this->record->cuentausuario->username_cuenta,
                'email_cuenta'    => $this->record->cuentausuario->email_cuenta,
                'number_cuenta'   => $this->record->cuentausuario->number_cuenta,
                'password_cuenta' => '********',
            ];

            if ($this->codigoValidado && $passCifrado) {
                try {
                    $decrypted = Crypt::decryptString($passCifrado);

                    $data['cuenta_usuario']['password_cuenta'] = $decrypted;

                    $data['view_password'] = $decrypted;

                } catch (DecryptException $e) {
                    $data['cuenta_usuario']['password_cuenta'] = 'Error al desencriptar';
                }
            } else {
                $data['view_password'] = '********';
            }
        }
        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('bloquear_acceso')
                ->label('Bloquear')
                ->icon('heroicon-o-lock-closed')
                ->color('danger')
                ->visible(fn () => $this->codigoValidado)
                ->action(function () {
                    $id = $this->record->id_cuenta;
                    session()->forget(["cuenta_validada_{$id}", "cuenta_hora_{$id}"]);
                    $this->codigoValidado = false;

                    $this->fillForm();

                    Notification::make()->warning()->title('Acceso cerrado')->send();
                }),

            Action::make('validar_codigo')
                ->label('Ver Credenciales')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->visible(fn () => !$this->codigoValidado)
                ->form([
                    TextInput::make('codigo')
                        ->label('Código de Seguridad')
                        ->password()
                        ->required(),
                ])
                ->action(function (array $data) {
                    $tokenValido = Token::where('number_token', $data['codigo'])
                        ->where('id_user', Auth::id())
                        ->where('estado_token', 1)
                        ->exists();

                    if ($tokenValido) {
                        $id = $this->record->id_cuenta;
                        session()->put("cuenta_validada_{$id}", true);
                        session()->put("cuenta_hora_{$id}", now());
                        $this->codigoValidado = true;

                        $this->fillForm();

                        Notification::make()
                            ->success()
                            ->title('Acceso concedido')
                            ->body("Mostrando credenciales por {$this->minutosExpiracion} minuto(s).")
                            ->send();
                    } else {
                        Notification::make()
                            ->danger()
                            ->title('Acceso denegado')
                            ->body('Código inválido.')
                            ->send();
                    }
                }),

            // EditAction::make(),
        ];
    }
}
