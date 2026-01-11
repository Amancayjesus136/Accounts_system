<?php

namespace App\Filament\Resources\Grupos\Pages;

use App\Filament\Resources\Grupos\GrupoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditGrupo extends EditRecord
{
    protected static string $resource = GrupoResource::class;

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Grupo actualizada correctamente';
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
                ->visible(fn () => $this->record->estado_grupo === 1)
                ->action(function () {
                    $this->record->update([
                        'estado_grupo' => 0,
                    ]);
                    return redirect(static::$resource::getUrl('index'));
                })
                ->successNotificationTitle('Grupo archivado correctamente'),

            Action::make('activar')
                ->label('Activar')
                ->icon('heroicon-o-check-circle')
                ->extraAttributes([
                    'class' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500 border-none [&>svg]:!text-white',
                    'style' => 'background-color: #16a34a !important; color: white !important;',
                ])
                ->requiresConfirmation()
                ->visible(fn () => $this->record->estado_grupo === 0)
                ->action(function () {
                    $this->record->update([
                        'estado_grupo' => 1,
                    ]);
                    return redirect(static::$resource::getUrl('index'));
                })
                ->successNotificationTitle('Grupo activado correctamente'),

            DeleteAction::make()
                ->icon('heroicon-o-trash'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index');
    }
}
