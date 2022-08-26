<?php

namespace TungTT\LaravelMap\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\MenuBuilder\Nova\Resources\MenuResource;

class BaseLayerMenu extends MenuResource
{
    public static function indexQuery(NovaRequest $request, $query)
    {
        return parent::indexQuery($request, $query)->where('slug', 'baselayer');
    }
}