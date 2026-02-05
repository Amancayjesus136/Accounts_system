<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Asignado;
use Illuminate\Auth\Access\HandlesAuthorization;

class AsignadoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Asignado');
    }

    public function view(AuthUser $authUser, Asignado $asignado): bool
    {
        return $authUser->can('View:Asignado');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Asignado');
    }

    public function update(AuthUser $authUser, Asignado $asignado): bool
    {
        return $authUser->can('Update:Asignado');
    }

    public function delete(AuthUser $authUser, Asignado $asignado): bool
    {
        return $authUser->can('Delete:Asignado');
    }

    public function restore(AuthUser $authUser, Asignado $asignado): bool
    {
        return $authUser->can('Restore:Asignado');
    }

    public function forceDelete(AuthUser $authUser, Asignado $asignado): bool
    {
        return $authUser->can('ForceDelete:Asignado');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Asignado');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Asignado');
    }

    public function replicate(AuthUser $authUser, Asignado $asignado): bool
    {
        return $authUser->can('Replicate:Asignado');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Asignado');
    }

}