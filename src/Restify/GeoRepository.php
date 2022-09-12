<?php
namespace TungTT\LaravelMap\Restify;

use App\Restify\Repository;
use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;
use Illuminate\Support\Facades\DB;
use MStaack\LaravelPostgis\Geometries\GeometryCollection;
use MStaack\LaravelPostgis\Geometries\MultiPolygon;
use TungTT\LaravelMap\Restify\Filters\IntersectsFilter;
use TungTT\LaravelMap\Restify\Getters\AttributesGetter;

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
                    return field('geometry', fn() => $this->{$name})->fillCallback(function (RestifyRequest $request, $model, $attribute) use($name) {
                        $geojson = json_encode($request->input('geometry'));

                        $statement = "ST_GeomFromGeoJSON('{$geojson}')";

                        if(json_decode($geojson, true)['type'] === 'Polygon'){
                            $statement = "ST_Multi(ST_GeomFromGeoJSON('{$geojson}'))";
                        }

                        $model->{$name} = DB::raw($statement);
                    });
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

    public function getters(RestifyRequest $request): array
    {
        return [
            AttributesGetter::new($this->getType($request))->onlyOnIndex()
        ];
    }
}
