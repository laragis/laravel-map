<?php

namespace TungTT\LaravelMap\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapLayer extends Model
{
    use HasFactory;

    protected $table = 'maps_layers';

    protected $casts = [
        'layer_params' => 'array',
        'popup' => 'array',
    ];

    public function service(){
        return $this->hasOne(MapService::class, 'id', 'service_id');
    }
}