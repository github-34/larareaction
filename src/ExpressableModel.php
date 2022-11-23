<?php

namespace Insomnicles\Laraexpress;

use Illuminate\Database\Eloquent\Model;

class ExpressableModel extends Model
{
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [];
}
