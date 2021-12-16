<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReactionType extends Model
{
    use HasFactory;

    public function isRangeInt()
    {
        return $this->range_type == 'int';
    }

    public function isRangeFloat()
    {
        return $this->range_type == 'float';
    }
}
