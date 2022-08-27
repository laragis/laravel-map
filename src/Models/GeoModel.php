<?php

namespace TungTT\LaravelMap\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class GeoModel extends Model
{
    use PostgisTrait;

    protected $postgisFields = [
        'geom',
        'the_geom',
    ];

    protected $geomField = 'geom';

    public function scopeWhereIntersects(Builder $query, $location)
    {
        return $query->whereRaw("ST_Intersects('SRID=4326;POINT({$location[1]} {$location[0]})', {$this->geomField})");
    }

    public function toGeoJSON()
    {
        return [];
    }

    public function toGeometry(){
        return [];
    }
}
