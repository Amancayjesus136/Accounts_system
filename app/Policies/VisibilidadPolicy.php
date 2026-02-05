<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Visibilidad;
use Illuminate\Auth\Access\HandlesAuthorization;

class VisibilidadPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Visibilidad');
    }

    public function view(AuthUser $authUser, Visibilidad $visibilidad): bool
    {
        return $authUser->can('View:Visibilidad');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Visibilidad');
    }

    public function update(AuthUser $authUser, Visibilidad $visibilidad): bool
    {
        return $authUser->can('Update:Visibilidad');
    }

    public function delete(AuthUser $authUser, Visibilidad $visibilidad): bool
    {
        return $authUser->can('Delete:Visibilidad');
    }

    public function restore(AuthUser $authUser, Visibilidad $visibilidad): bool
    {
        return $authUser->can('Restore:Visibilidad');
    }

    public function forceDelete(AuthUser $authUser, Visibilidad $visibilidad): bool
    {
        return $authUser->can('ForceDelete:Visibilidad');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Visibilidad');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Visibilidad');
    }

    public function replicate(AuthUser $authUser, Visibilidad $visibilidad): bool
    {
        return $authUser->can('Replicate:Visibilidad');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Visibilidad');
    }

}