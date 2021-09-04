<?php

namespace Amirsarfar\DynaBase\Facades;

use Illuminate\Support\Facades\Facade;

class DynaBase extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'dyna-base';
    }
}
