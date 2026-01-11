<?php

namespace App\Filament\Resources\Visibilidads\Pages;

use App\Filament\Resources\Visibilidads\VisibilidadResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVisibilidad extends EditRecord
{
    protected static string $resource = VisibilidadResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Action::make('archivar')
                ->label('Archivar')
                ->icon('heroicon-o-archive-box')
                ->color('warning')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->estado_visibilidad === 1)
                ->action(function () {
                    $this->record->update([
                        'estado_visibilidad' => 0,
                    ]);

                    return redirect(
                        static::$resource::getUrl('index')
                    );
                })
                ->successNotificationTitle('Registro archivado'),

            Action::make('activar')
                ->label('Activar')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->estado_visibilidad === 0)
                ->action(function () {
                    $this->record->update([
                        'estado_visibilidad' => 1,
                    ]);

                    return redirect(
                        static::$resource::getUrl('index')
                    );
                })
                ->successNotificationTitle('Registro activado'),

            // DeleteAction::make(),
        ];
    }
}
