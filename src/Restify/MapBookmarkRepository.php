<?php

namespace TungTT\LaravelMap\Restify;

use App\Restify\Repository;
use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;
use TungTT\LaravelMap\Models\MapBookmark;

class MapBookmarkRepository extends Repository
{
    public static string $model = MapBookmark::class;

    public static string $uriKey = 'map_bookmark';

    public function fields(RestifyRequest $request): array
    {
        return [
            id(),
            field('title')->required(),
            field('description'),
            field('icon'),
            field('bounds'),
            field('geometry')->required(),
        ];
    }


}
