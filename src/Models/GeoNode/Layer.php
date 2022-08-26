<?php

namespace TungTT\LaravelMap\Models\GeoNode;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Layer extends GeoNodeModel
{
    protected $table = 'layers_layer';

    protected $casts = [

    ];

    /**
     * Get the user's first name.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function styles(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => json_decode(Str::replace('\'', '"', $attributes['styles'])),
        );
    }
}
