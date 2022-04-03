<?php

namespace App\Express\Policies;

use App\Express\Models\Expression;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpressionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Expression  $expression
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Expression $expression)
    {
        return $user->id == $expression->user_id;
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
     * @param  \App\Models\Expression  $expression
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Expression $expression)
    {
        return $user->id == $expression->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Expression  $expression
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Expression $expression)
    {
        return $user->id == $expression->user_id;
    }

    /**
     * Determine whether the user can view any expressable expression
     *
     * @param  \App\Models\User|null $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewStats(?User $user)
    {
        return true;
    }
}
