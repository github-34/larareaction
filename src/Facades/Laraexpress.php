<?php

namespace Insomnicles\Laraexpress\Facades;

use Illuminate\Support\Facades\Facade;

class Laraexpress extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'laraexpress';
    }
}
