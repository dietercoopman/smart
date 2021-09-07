<?php

namespace Dietercoopman\Smart;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Dietercoopman\Smart\Smart
 */
class SmartFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'skeleton';
    }
}
