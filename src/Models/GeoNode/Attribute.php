<?php

namespace TungTT\LaravelMap\Models\GeoNode;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $connection = 'geonode';

    protected $table = 'layers_attribute';

    public function layer(){
        return $this->belongsTo(Layer::class, 'layer_id', 'resourcebase_ptr_id');
    }
}
