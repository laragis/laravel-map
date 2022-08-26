<?php

namespace TungTT\LaravelMap\Models;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class GeoModel extends Model
{
    use PostgisTrait;

    protected $postgisFields = [
        'geom',
        'the_geom',
    ];
}
