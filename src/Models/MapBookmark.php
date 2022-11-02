<?php

namespace TungTT\LaravelMap\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapBookmark extends Model
{
    use HasFactory;

    protected $table = 'maps_bookmarks';

    protected $fillable = [
        'title',
        'description',
        'geometry',
    ];

    protected $casts = [
        'bounds' => 'array',
        'geometry' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->user_id = auth()->user()?->id;
        });
    }
}