<?php

namespace TungTT\LaravelMap\Restify;

use App\Restify\Repository;
use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
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
            field('user_id')->storeCallback(function () {
                return optional(auth()->user())->id;
            }),
        ];
    }

    public static function indexQuery(RestifyRequest $request, Relation|Builder $query)
    {
        return parent::indexQuery($request, $query)->where('user_id', optional(auth()->user())->id);
    }
}
