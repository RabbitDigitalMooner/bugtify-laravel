<?php

namespace RabbitDigital\Bugtify\Facades;

use Illuminate\Support\Facades\Facade;

class Bugtify extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'bugtify';
    }
}
