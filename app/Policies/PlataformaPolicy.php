<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Plataforma;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlataformaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Plataforma');
    }

    public function view(AuthUser $authUser, Plataforma $plataforma): bool
    {
        return $authUser->can('View:Plataforma');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Plataforma');
    }

    public function update(AuthUser $authUser, Plataforma $plataforma): bool
    {
        return $authUser->can('Update:Plataforma');
    }

    public function delete(AuthUser $authUser, Plataforma $plataforma): bool
    {
        return $authUser->can('Delete:Plataforma');
    }

    public function restore(AuthUser $authUser, Plataforma $plataforma): bool
    {
        return $authUser->can('Restore:Plataforma');
    }

    public function forceDelete(AuthUser $authUser, Plataforma $plataforma): bool
    {
        return $authUser->can('ForceDelete:Plataforma');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Plataforma');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Plataforma');
    }

    public function replicate(AuthUser $authUser, Plataforma $plataforma): bool
    {
        return $authUser->can('Replicate:Plataforma');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Plataforma');
    }

}