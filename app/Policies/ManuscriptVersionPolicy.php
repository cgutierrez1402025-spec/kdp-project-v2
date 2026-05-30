<?php

namespace App\Policies;

use App\Models\ManuscriptVersion;
use App\Models\User;

class ManuscriptVersionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_works');
    }

    public function view(User $user, ManuscriptVersion $manuscriptVersion): bool
    {
        return $user->id === $manuscriptVersion->created_by || $user->can('view_works');
    }

    public function create(User $user): bool
    {
        return $user->can('create_works');
    }

    public function update(User $user, ManuscriptVersion $manuscriptVersion): bool
    {
        return $user->id === $manuscriptVersion->created_by || $user->can('edit_works');
    }

    public function delete(User $user, ManuscriptVersion $manuscriptVersion): bool
    {
        return $user->can('delete_works');
    }
}
