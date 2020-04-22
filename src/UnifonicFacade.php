<?php

namespace Multicaret\Unifonic;

use Illuminate\Support\Facades\Facade;


class UnifonicFacade extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'unifonic';
    }
}
