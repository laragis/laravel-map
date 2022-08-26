<?php

namespace TungTT\LaravelMap\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Outl1ne\MenuBuilder\Models\Menu;

class MapMap extends Model
{
    use HasFactory;

    protected $table = 'maps_maps';

    protected $casts = [
        'center_x' => 'float',
        'center_y' => 'float',
        'zoom' => 'float',
        'published_at' => 'datetime',
        'search_apis' => 'json',
    ];

    public function baseLayerMenu(){
        return $this->belongsTo(Menu::class, 'baselayer_menu_id')->where('slug', 'baselayer');
    }

    public function baseLayerSelected(){
        return $this->belongsTo(MapLayer::class, 'baselayer_selected');
    }

    public function layerMenu(){
        return $this->belongsTo(Menu::class, 'layer_menu_id')->where('slug', 'layer');
    }
}