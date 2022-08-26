<?php

namespace TungTT\LaravelMap\Nova;

use App\Nova\Resource;
use Carbon\Carbon;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MultiSelect;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Outl1ne\MenuBuilder\Nova\Resources\MenuResource;

class MapMap extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \TungTT\LaravelMap\Models\MapMap::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title',
    ];

    public static function group()
    {
        return __('Map');
    }

    public static function label()
    {
        return __('Map');
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return parent::indexQuery($request, $query)->with('baseLayerSelected');
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
            new Panel(__('General'), [
                ID::make()->sortable(),
                Text::make(__('Title'), 'title')->rules('required'),
                Textarea::make(__('Abstract'), 'abstract'),
                Number::make('Center Y (Lat)', 'center_y')->step(1e-15)->rules('required')->hideFromIndex(),
                Number::make('Center X (Lng)', 'center_x')->step(1e-15)->rules('required')->hideFromIndex(),
                Number::make('Zoom', 'zoom')->rules('required')->hideFromIndex(),
                Text::make('Projection', 'projection')->default('EPSG:4326')->rules('required')->hideFromIndex(),
                DateTime::make('Published At', 'published_at')->default(Carbon::now())->rules('required')->hideFromIndex(),
            ]),

            new Panel(__('Basemap'), [
                BelongsTo::make($request->isResourceIndexRequest() ? 'Basemap Menu' : 'Menu', 'baseLayerMenu', BaseLayerMenu::class)->showCreateRelationButton(),
                Select::make($request->isResourceIndexRequest() ? 'Basemap Selected' : 'Selected', 'baselayer_selected')->dependsOn(['baseLayerMenu'], function (Select $field, NovaRequest $request, FormData $formData){
                    if($baseLayerMenu = $formData->baseLayerMenu){
                        $menuItems = nova_get_menu_by_id($baseLayerMenu);
                        $field->options($menuItems['menuItems']->pluck('name', 'value'));
                    }
                })->displayUsing(function ($value){
                    return $this->baseLayerSelected?->title;
                }),
            ]),

            new Panel(__('Layers'), [
                BelongsTo::make('Layer', 'layerMenu', LayerMenu::class)->showCreateRelationButton(),
            ]),

            new Panel(__('Search'), [
                Select::make($request->isResourceIndexRequest() ? 'Search Provider' : 'Provider', 'search_provider')->default('google')->options([
                    'google' => 'Google Maps',
//                    'osm' => 'OpenStreetMap'
                ])->displayUsingLabels(),

                MultiSelect::make('APIs', 'search_apis')->options(function (){
                    return \TungTT\LaravelMap\Models\MapApi::pluck('title', 'id');
                })->hideFromIndex()
            ]),

            DateTime::make('Published At', 'published_at')->onlyOnIndex(),

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