<?php

namespace TungTT\LaravelMap\Nova;

use App\Nova\Resource;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class MapBaseLayer extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \TungTT\LaravelMap\Models\MapLayer::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static function group()
    {
        return __('Map');
    }

    public static function label()
    {
        return __('Map Baselayer');
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return parent::indexQuery($request, $query)->where('group', 'baselayer');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Title'), 'title')->sortable()->rules('required'),
            Select::make(__('Type'), 'type')->options([
                'xyz' => 'XYZ',
                'wmts' => 'WMTS',
                'wms' => 'WMS',
            ])->displayUsingLabels()->default('xyz')->rules('required'),
            Text::make(__('URL'), 'url')->hideFromIndex()->rules('required'),

            KeyValue::make(__('Options'), 'layer_params')->dependsOn(['type'], function (KeyValue $field, NovaRequest $request, FormData $formData){
                if($formData->type === 'xyz') $field->help('<b>Keys</b>: (minZoom, maxZoom, subdomains, errorTileUrl, zoomOffset, tms, zoomReverse, detectRetina, crossOrigin, referrerPolicy), (attribution)');
                else if($formData->type === 'wms') $field->help('<b>Keys</b>: (minZoom, maxZoom, subdomains, errorTileUrl, zoomOffset, tms, zoomReverse, detectRetina, crossOrigin, referrerPolicy), (layers, styles, format, transparent, version, crs, uppercase), (attribution)');
                else if($formData->type === 'wmts') $field->help('<b>Keys</b>: (minZoom, maxZoom, subdomains, errorTileUrl, zoomOffset, tms, zoomReverse, detectRetina, crossOrigin, referrerPolicy), (layers, styles, format, transparent, version, crs, uppercase), (attribution)');
            }),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}