<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Presupuesto;
use Illuminate\Auth\Access\HandlesAuthorization;

class PresupuestoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Presupuesto');
    }

    public function view(AuthUser $authUser, Presupuesto $presupuesto): bool
    {
        return $authUser->can('View:Presupuesto');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Presupuesto');
    }

    public function update(AuthUser $authUser, Presupuesto $presupuesto): bool
    {
        return $authUser->can('Update:Presupuesto');
    }

    public function delete(AuthUser $authUser, Presupuesto $presupuesto): bool
    {
        return $authUser->can('Delete:Presupuesto');
    }

    public function restore(AuthUser $authUser, Presupuesto $presupuesto): bool
    {
        return $authUser->can('Restore:Presupuesto');
    }

    public function forceDelete(AuthUser $authUser, Presupuesto $presupuesto): bool
    {
        return $authUser->can('ForceDelete:Presupuesto');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Presupuesto');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Presupuesto');
    }

    public function replicate(AuthUser $authUser, Presupuesto $presupuesto): bool
    {
        return $authUser->can('Replicate:Presupuesto');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Presupuesto');
    }

}