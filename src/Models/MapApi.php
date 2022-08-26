<?php

namespace TungTT\LaravelMap\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapApi extends Model
{
    use HasFactory;

    protected $table = 'maps_apis';

    protected $casts = [
        'search_fields' => 'array',
        'display_fields' => 'array',
        'fillable_fields' => 'array',
    ];
}