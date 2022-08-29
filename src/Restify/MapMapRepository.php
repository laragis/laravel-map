<?php

namespace TungTT\LaravelMap\Restify;

use App\Restify\Repository;
use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;
use TungTT\LaravelMap\Models\MapMap;

class MapMapRepository extends Repository
{
    public static string $model = MapMap::class;

    public static string $uriKey = 'map_map';

    public function fields(RestifyRequest $request): array
    {
        return [
            id(),
            field('title'),
            field('abstract'),
            field('center_x'),
            field('center_y'),
        ];
    }
}
