<?php

namespace TungTT\LaravelMap\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapBookmark extends Model
{
    use HasFactory;

    protected $table = 'maps_bookmarks';

    protected $casts = [
        'bounds' => 'array',
        'geometry' => 'array',
    ];
}