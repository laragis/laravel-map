<?php
namespace TungTT\LaravelMap\Restify;

use App\Restify\Repository;
use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;
use TungTT\LaravelMap\Restify\Filters\IntersectsFilter;

class GeoRepository extends Repository
{
    public function fields(RestifyRequest $request): array
    {
        $model = app(static::$model);
        $display = collect($model::$display);
        $primaryKey = $model->getKeyName();

        return [
            $primaryKey === 'id' ? id() : field($primaryKey)->label('id')->readonly(),
            ...collect($display)->map(function ($name) use($model, $primaryKey) {
                if(property_exists($model, 'postgisFields') && in_array($name, $model->getPostgisFields())){
                    return field('geometry', fn() => $this->{$name});
                }

                return field($name);
            }),
        ];
    }

    public static function searchables(): array
    {
        if(!empty(static::$search)) return static::$search;
        
        if(!empty(static::$model::$search)) return static::$model::$search;

        return [static::newModel()->getKeyName()];
    }

    public function title(): string
    {
        return $this->{static::$title} ?? "";
    }

    public function filters(RestifyRequest $request): array
    {
        return [
            IntersectsFilter::new(),
        ];
    }
}
