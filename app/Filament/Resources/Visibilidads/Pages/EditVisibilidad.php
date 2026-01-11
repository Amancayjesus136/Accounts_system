<?php

namespace App\Filament\Resources\Visibilidads\Pages;

use App\Filament\Resources\Visibilidads\VisibilidadResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVisibilidad extends EditRecord
{
    protected static string $resource = VisibilidadResource::class;

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Visibilidad actualizada correctamente';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('archivar')
                ->label('Archivar')
                ->icon('heroicon-o-archive-box')
                ->extraAttributes([
                    'class' => 'bg-yellow-500 text-white hover:bg-yellow-600 focus:ring-yellow-500 border-none [&>svg]:!text-white',
                    'style' => 'background-color: #f49200 !important; color: white !important;',
                ])
                ->requiresConfirmation()
                ->visible(fn () => $this->record->estado_visibilidad === 1)
                ->action(function () {
                    $this->record->update([
                        'estado_visibilidad' => 0,
                    ]);
                    return redirect(static::$resource::getUrl('index'));
                })
                ->successNotificationTitle('Registro archivado correctamente'),

            Action::make('activar')
                ->label('Activar')
                ->icon('heroicon-o-check-circle')
                ->extraAttributes([
                    'class' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500 border-none [&>svg]:!text-white',
                    'style' => 'background-color: #16a34a !important; color: white !important;',
                ])
                ->requiresConfirmation()
                ->visible(fn () => $this->record->estado_visibilidad === 0)
                ->action(function () {
                    $this->record->update([
                        'estado_visibilidad' => 1,
                    ]);
                    return redirect(static::$resource::getUrl('index'));
                })
                ->successNotificationTitle('Registro activado correctamente'),

            DeleteAction::make()
                ->icon('heroicon-o-trash'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index');
    }
}
