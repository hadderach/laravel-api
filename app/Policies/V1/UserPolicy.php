<?php

namespace App\Policies\V1;

use App\Models\Ticket;
use App\Models\User;
use App\Permissions\V1\Abilities;
use Auth;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function delete(User $user, Ticket $ticket)
    {
        return $user->tokenCan(Abilities::DeleteUser);

    }

    public function replace(User $user, Ticket $ticket)
    {
        return $user->tokenCan(Abilities::ReplaceUser);
    }

    public function store(User $user)
    {
        return $user->tokenCan(Abilities::CreateUser);
    }

    public function update(User $user, Ticket $ticket)
    {
        return $user->tokenCan(Abilities::UpdateUser);
    }
}