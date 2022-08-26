<?php

namespace TungTT\LaravelMap;

use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;

use Laravel\Nova\Tool;

class NovaMapTool extends Tool
{
    public function boot()
    {

    }

    /**
     * Build the menu that renders the navigation links for the tool.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function menu(Request $request)
    {
        return MenuSection::make(__('Map'), [
            MenuItem::resource(\TungTT\LaravelMap\Nova\MapMap::class),
            MenuItem::resource(\TungTT\LaravelMap\Nova\MapService::class),
            MenuItem::resource(\TungTT\LaravelMap\Nova\MapBaseLayer::class),
            MenuItem::resource(\TungTT\LaravelMap\Nova\MapLayer::class),
            MenuItem::resource(\TungTT\LaravelMap\Nova\MapApi::class),
            MenuItem::resource(\TungTT\LaravelMap\Nova\MapBookmark::class),
        ])->icon('map')->collapsible();
    }
}
