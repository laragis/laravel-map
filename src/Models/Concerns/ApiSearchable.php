<?php
namespace TungTT\LaravelMap\Models\Concerns;

use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

trait ApiSearchable
{
    use Searchable;

    public function searchableAs()
    {
        return Str::snake(class_basename(get_class()));
    }

    public function toSearchableArray()
    {
        $primaryKey = $this->getKeyName();

        return collect([
            'id' => $this->{$primaryKey}
        ])->merge(collect($this->toArray())->only(static::$display)->mapWithKeys(function ($value, $key){
            if(in_array($key, ['fid', 'gid', 'id'])) return ['id' => $value];

            if(in_array($key, ['geom', 'the_geom'])) return ['geometry' => $value];

            return [$key => $value];
        }))->all();
    }
}