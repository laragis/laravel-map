<?php

namespace TungTT\LaravelMap\Models\GeoNode;

use Illuminate\Database\Eloquent\Model;

class ResourceBase extends Model
{
    protected $connection = 'geonode';

    protected $table = 'base_resourcebase';
}
