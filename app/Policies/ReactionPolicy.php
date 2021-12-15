<?php

namespace App\Policies;

use App\Models\Reaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReactionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User|null  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Reaction  $reaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Reaction $reaction)
    {
        return $user->id == $reaction->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Reaction  $reaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Reaction $reaction)
    {
        return $user->id == $reaction->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Reaction  $reaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Reaction $reaction)
    {
        return $user->id == $reaction->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Reaction  $reaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Reaction $reaction)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Reaction  $reaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Reaction $reaction)
    {
        return false;
    }

    /**
     * Determine whether the user can view any reaction type
     *
     * @param  \App\Models\User|null $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAnyReactionType(?User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view any reactable reaction
     *
     * @param  \App\Models\User|null $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewStats(?User $user, Reaction $reaction)
    {
        return true;
    }
}
