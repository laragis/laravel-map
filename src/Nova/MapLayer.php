<?php

namespace TungTT\LaravelMap\Nova;

use App\Facades\GeoNode;
use App\Nova\Resource;
use Ganyicz\NovaCallbacks\HasCallbacks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class MapLayer extends Resource
{
    use HasCallbacks;
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
        return __('Map Layer');
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return parent::indexQuery($request, $query)->where('group', 'layer');
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

            Text::make(__('URL'), 'url')->hideFromIndex()->dependsOn(['service_id'], function (Text $field, NovaRequest $request, FormData $formData) {
                if ($formData->service_id) {
                    $field->value = '';
                    $field->hide();
                } else $field->rules('required');
            })->hideFromDetail(function () {
                return !!$this->service_id;
            }),

            Select::make(__('Service'), 'service_id')->options(function () {
                return \TungTT\LaravelMap\Models\MapService::where('type', 'wms')->pluck('name', 'id');
            })
                ->nullable()->displayUsingLabels()
                ->dependsOn(['url'], function (Select $field, NovaRequest $request, FormData $formData) {
                    if ($formData->url) $field->hide();
                })->hideFromDetail(function () {
                    return !!$this->url;
                }),

            Select::make(__('Type'), 'layer_params->type')->options(['wms' => 'WMS', 'wmts' => 'WMTS']),

            Select::make(__('Resource'), 'layer_params->params->layers')->options(function () {
                return GeoNode::layerResourcesByUser()->pluck('title', 'alternate');
            })
                ->hideFromIndex()
                ->hide()
                ->searchable()
                ->dependsOn(['service_id'], function (Select $field, NovaRequest $request, FormData $formData) {
                    if ($formData->service_id) $field->show();
                }),


            Text::make(__('Styles'), 'styles'),
            Number::make(__('Opacity'), 'opacity')->default(1)->step(0.1),
            Number::make('zIndex', 'layer_params->zIndex'),
            Boolean::make(_('Visibility'), 'visibility')->default(true),

            new Panel(__('Popup'), [
                Boolean::make(__('Enabled'), 'popup')->fillUsing(function ($request, $model, $attribute, $requestAttribute) {
                    if ($request->input($attribute)) {
                        $model->{$attribute} = array_merge(is_null($model->{$attribute}) ? [] : $model->{$attribute}, ['enabled' => true]);
                    } else $model->{$attribute} = null;

                })->hideFromIndex(),
            ])
        ];
    }

    public static function beforeSave(Request $request, $model)
    {
        $model->group = 'layer';
        $model->visibility = true;

        if ($model->service_id) $model->url = null;
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