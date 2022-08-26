<?php

namespace TungTT\LaravelMap\Nova;

use App\Nova\Resource;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MultiSelect;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Mostafaznv\NovaCkEditor\CkEditor;
use TungTT\AjaxSelect\AjaxSelect;

class MapApi extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \TungTT\LaravelMap\Models\MapApi::class;

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
        return __('Map API');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        $api_table = 'maps_apis';

        $geodatabase_connection_name = env('GEODATABASE_CONNECTION_NAME', 'default');

        $getFields = function (MultiSelect $field, NovaRequest $request, FormData $formData){
            if($table = $formData->table){
                $field->show();

                $attrs = Schema::connection($formData->connection)->getColumnListing($table);
                $field->options(array_combine($attrs, $attrs));
            }
        };

        return [
            ID::make()->sortable(),

            Text::make(__('Title'), 'title')->sortable(),
            Textarea::make(__('Description'), 'description'),
            Slug::make(__('Name'), 'name')
                ->from('title')
                ->separator('_')
                ->rules('required')
                ->creationRules("unique:{$api_table},name")
                ->updateRules("unique:{$api_table},name,{{resourceId}}")
                ->fillUsing(function ($request, $model, $attribute, $requestAttribute) {
                    $model->{$attribute} = Str::slug($request->input($attribute), '_');
                })
                ->sortable(),
//            Slug::make(__('URI Key'), 'uri_key')->from('title')->rules('required')->hideFromIndex(),
            Text::make(__('Connection'), 'connection')->default($geodatabase_connection_name)->hideFromIndex(),
//            AjaxSelect::make(__('Resource'), 'table')->get('/api/geonode/layers'),
            Select::make(__('Resource'), 'table')->options(function (){
                return \TungTT\LaravelMap\Models\GeoNode\Layer::pluck('title_en', 'name');
            })->searchable(),
            Textarea::make(__('Columns'), 'columns')->hide()->dependsOn(['table', 'connection'], function (Textarea $field, NovaRequest $request, FormData $formData){
                if($table = $formData->table){
                    $field->show();

                    $attrsStr = collect(Schema::connection($formData->connection)->getColumnListing($table))->join(', ');
                    $field->value = $attrsStr;
                }
            })
//                ->resolveUsing(function ($name) use($geodatabase_connection_name){
//                    return collect(Schema::connection($geodatabase_connection_name)->getColumnListing($this->resource->table))->join(', ');;
//                })
                ->readonly()->rows(2),
            Text::make(__('Title Template'), 'title_template')->hideFromIndex(),
//            CkEditor::make(__('Body Template'), 'body_template')->hideFromIndex(),
            Textarea::make(__('Body Template'), 'body_template')->hideFromIndex(),

            MultiSelect::make(__('Search Fields'), 'search_fields')->hide()->dependsOn(['table', 'connection'], $getFields)->displayUsingLabels()->nullable()->hideFromIndex(),
            MultiSelect::make(__('Display Fields'), 'display_fields')->hide()->dependsOn(['table', 'connection'], $getFields)->displayUsingLabels()->nullable()->hideFromIndex(),
            MultiSelect::make(__('Fillable Fields'), 'fillable_fields')->hide()->dependsOn(['table', 'connection'], $getFields)->displayUsingLabels()->nullable()->hideFromIndex(),

            Hidden::make('model_type')->fillUsing(function ($request, $model, $attribute, $requestAttribute){
                $modelClass = (string)Str::of($model->name)->slug('_')->camel()->ucfirst();
                $model->{$attribute} = 'App\Models\Api\\'.$modelClass;
            }),

            Boolean::make('Scout', 'scout'),
            Boolean::make('Status', 'status')->default(1),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}