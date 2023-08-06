<?php

namespace PGT\Logsnag\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \PGT\Logsnag\Logsnag
 */
class Logsnag extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \PGT\Logsnag\Logsnag::class;
    }
}
