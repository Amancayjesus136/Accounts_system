<?php

namespace App\Filament\Resources\Asignados\Pages;

use App\Filament\Resources\Asignados\AsignadoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListAsignados extends ListRecords
{
    protected static string $resource = AsignadoResource::class;

    protected $listeners = [
        'refreshTabs' => '$refresh',
    ];

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }


    // public function getTabs(): array
    // {
    //     return [
    //         // 'todos' => Tab::make('Todos')
    //         //     ->icon('heroicon-m-list-bullet')
    //         //     ->badge(static::getResource()::getModel()::where('id_usuario', Auth::id())->count())
    //         //     ->badgeColor('gray'),

    //         'integrantes' => Tab::make('Aceptados')
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('estado_asignado', 1))
    //             ->icon('heroicon-m-check-circle')
    //             ->live()
    //             ->badge(static::getResource()::getModel()::where('id_usuario', Auth::id())->where('estado_asignado', 1)->count())
    //             ->badgeColor('success'),

    //         'pendientes' => Tab::make('Pendientes')
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('estado_asignado', 2))
    //             ->icon('heroicon-m-clock')
    //             ->live()
    //             ->badge(static::getResource()::getModel()::where('id_usuario', Auth::id())->where('estado_asignado', 2)->count())
    //             ->badgeColor('warning'),

    //         'rechazados' => Tab::make('Rechazados')
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('estado_asignado', 0))
    //             ->icon('heroicon-m-x-circle')
    //             ->live()
    //             ->badge(static::getResource()::getModel()::where('id_usuario', Auth::id())->where('estado_asignado', 0)->count())
    //             ->badgeColor('danger'),
    //     ];
    // }
}
