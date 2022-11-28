<?php

namespace TungTT\LaravelMap\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MapBookmark extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $table = 'maps_bookmarks';

    protected $fillable = [
        'title',
        'description',
        'radius',
        'geometry',
    ];

    protected $casts = [
        'bounds' => 'array',
        'geometry' => 'array',
        'radius' => 'float',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->user_id = auth()->user()?->id;
        });
    }
}
