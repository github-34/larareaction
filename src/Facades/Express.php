<?php

namespace App\Express\Facades;

use Illuminate\Support\Facades\Facade;

class Express extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'Express';
    }
}
