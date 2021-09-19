<?php

namespace Limsum\Facades;

use Illuminate\Support\Facades\Facade;

class Limsum extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'limsum';
    }
}
