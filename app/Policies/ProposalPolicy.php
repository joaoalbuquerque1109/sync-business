<?php

namespace App\Policies;

use App\Models\Proposal;
use App\Models\User;

class ProposalPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->roles()->exists();
    }

    public function view(User $user, Proposal $proposal): bool
    {
        return $user->id === $proposal->responsible_user_id || $user->roles()->where('slug', 'admin')->exists();
    }

    public function create(User $user): bool
    {
        return $user->roles()->exists();
    }

    public function update(User $user, Proposal $proposal): bool
    {
        return $this->view($user, $proposal);
    }

    public function export(User $user, Proposal $proposal): bool
    {
        return $this->view($user, $proposal);
    }

    public function approve(User $user, Proposal $proposal): bool
    {
        return $user->roles()->whereIn('slug', ['admin', 'gestor'])->exists();
    }
}
