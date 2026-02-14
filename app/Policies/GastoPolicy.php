<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Gasto;
use Illuminate\Auth\Access\HandlesAuthorization;

class GastoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Gasto');
    }

    public function view(AuthUser $authUser, Gasto $gasto): bool
    {
        return $authUser->can('View:Gasto');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Gasto');
    }

    public function update(AuthUser $authUser, Gasto $gasto): bool
    {
        return $authUser->can('Update:Gasto');
    }

    public function delete(AuthUser $authUser, Gasto $gasto): bool
    {
        return $authUser->can('Delete:Gasto');
    }

    public function restore(AuthUser $authUser, Gasto $gasto): bool
    {
        return $authUser->can('Restore:Gasto');
    }

    public function forceDelete(AuthUser $authUser, Gasto $gasto): bool
    {
        return $authUser->can('ForceDelete:Gasto');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Gasto');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Gasto');
    }

    public function replicate(AuthUser $authUser, Gasto $gasto): bool
    {
        return $authUser->can('Replicate:Gasto');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Gasto');
    }

}