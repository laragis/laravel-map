<?php

namespace TungTT\LaravelMap\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \TungTT\LaravelMap\LaravelMap
 */
class LaravelMap extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \TungTT\LaravelMap\LaravelMap::class;
    }
}
