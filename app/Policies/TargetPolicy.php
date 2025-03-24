<?php

namespace App\Policies;

use App\Models\Target;
use App\Models\User;

class TargetPolicy
{
    public function update(User $user, Target $target): bool
    {
        return $user->id === $target->user_id;
    }

    public function delete(User $user, Target $target): bool
    {
        return $user->id === $target->user_id;
    }
}
