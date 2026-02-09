<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Tarjeta;
use Illuminate\Auth\Access\HandlesAuthorization;

class TarjetaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Tarjeta');
    }

    public function view(AuthUser $authUser, Tarjeta $tarjeta): bool
    {
        return $authUser->can('View:Tarjeta');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Tarjeta');
    }

    public function update(AuthUser $authUser, Tarjeta $tarjeta): bool
    {
        return $authUser->can('Update:Tarjeta');
    }

    public function delete(AuthUser $authUser, Tarjeta $tarjeta): bool
    {
        return $authUser->can('Delete:Tarjeta');
    }

    public function restore(AuthUser $authUser, Tarjeta $tarjeta): bool
    {
        return $authUser->can('Restore:Tarjeta');
    }

    public function forceDelete(AuthUser $authUser, Tarjeta $tarjeta): bool
    {
        return $authUser->can('ForceDelete:Tarjeta');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Tarjeta');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Tarjeta');
    }

    public function replicate(AuthUser $authUser, Tarjeta $tarjeta): bool
    {
        return $authUser->can('Replicate:Tarjeta');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Tarjeta');
    }

}