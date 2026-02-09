<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Ingreso;
use Illuminate\Auth\Access\HandlesAuthorization;

class IngresoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Ingreso');
    }

    public function view(AuthUser $authUser, Ingreso $ingreso): bool
    {
        return $authUser->can('View:Ingreso');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Ingreso');
    }

    public function update(AuthUser $authUser, Ingreso $ingreso): bool
    {
        return $authUser->can('Update:Ingreso');
    }

    public function delete(AuthUser $authUser, Ingreso $ingreso): bool
    {
        return $authUser->can('Delete:Ingreso');
    }

    public function restore(AuthUser $authUser, Ingreso $ingreso): bool
    {
        return $authUser->can('Restore:Ingreso');
    }

    public function forceDelete(AuthUser $authUser, Ingreso $ingreso): bool
    {
        return $authUser->can('ForceDelete:Ingreso');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Ingreso');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Ingreso');
    }

    public function replicate(AuthUser $authUser, Ingreso $ingreso): bool
    {
        return $authUser->can('Replicate:Ingreso');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Ingreso');
    }

}