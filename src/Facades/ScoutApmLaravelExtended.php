<?php

namespace Mamitech\ScoutApmLaravelExtended\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mamitech\ScoutApmLaravelExtended\ScoutApmLaravelExtended
 */
class ScoutApmLaravelExtended extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Mamitech\ScoutApmLaravelExtended\ScoutApmLaravelExtended::class;
    }
}
