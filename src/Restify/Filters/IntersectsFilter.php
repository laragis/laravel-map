<?php
namespace TungTT\LaravelMap\Restify\Filters;

use Binaryk\LaravelRestify\Filters\AdvancedFilter;
use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

class IntersectsFilter extends AdvancedFilter
{
    public function filter(RestifyRequest $request, Relation|Builder $query, $value)
    {
        $geojson = json_encode($value);

        $query->when($value, fn($q) => $q->whereRaw("ST_Intersects(ST_SetSRID(ST_GeomFromGeoJSON('{$geojson}'), 4326), the_geom)"));
    }

    public function rules(Request $request): array
    {
        return [];
    }

};

