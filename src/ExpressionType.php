<?php

namespace Insomnicles\Laraexpress;

use Illuminate\Database\Eloquent\Model;

class ExpressionType extends Model
{
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'created_from',
        'updated_from',
        'created_at',
        'updated_at',
        'deleted_at',
        'deleted_from',
    ];

    public function isRangeInt()
    {
        return $this->range_type == 'int';
    }

    public function isRangeFloat()
    {
        return $this->range_type == 'float';
    }
}
