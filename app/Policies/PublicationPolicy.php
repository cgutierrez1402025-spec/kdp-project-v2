<?php

namespace App\Policies;

use App\Models\Publication;
use App\Models\User;

class PublicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_works');
    }

    public function view(User $user, Publication $publication): bool
    {
        return $user->id === $publication->work->user_id || $user->can('view_works');
    }

    public function create(User $user): bool
    {
        return $user->can('create_works');
    }

    public function update(User $user, Publication $publication): bool
    {
        return $user->id === $publication->work->user_id || $user->can('edit_works');
    }

    public function delete(User $user, Publication $publication): bool
    {
        return $user->can('delete_works');
    }
}
