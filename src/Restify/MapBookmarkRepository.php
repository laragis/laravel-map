<?php

namespace TungTT\LaravelMap\Restify;

use App\Restify\Repository;
use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;
use File;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;
use TungTT\LaravelMap\Imports\MapBookmarkImport;
use TungTT\LaravelMap\Models\MapBookmark;

class MapBookmarkRepository extends Repository
{
    public static string $model = MapBookmark::class;

    public static string $uriKey = 'map_bookmark';

    public static string $title = 'title';

    public static array $search = ['title'];

    public static array $sort = ['id', 'title'];

    public static array $match = [
        'title' => 'string',
        'description' => 'string',
    ];

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

    public static function indexQuery(RestifyRequest $request, Builder | Relation $query)
    {
        return parent::indexQuery($request, $query)->where('user_id', auth()->user()?->id);
    }

    public static function routes(Router $router, $attributes = [], $wrap = true)
    {
        $router->get('export', [static::class, 'export'])->middleware('auth.session');
        $router->post('import', [static::class, 'import'])->middleware('auth.session');
    }

    public function export(Request $request)
    {
        if(!auth()->check()) abort(403);

        $ids = explode(',', $request->input('ids'));

        $models = static::$model::where('user_id', auth()->user()?->id);

        if(!empty($ids)) $models = $models->whereIn('id', $ids);

        $data = [
            'type' => 'FeatureCollection',
            'features' => $models->get()->map(fn($model) => [
                'type' => 'Feature',
                'geometry' => $model->geometry,
                'properties' => Arr::only($model->toArray(), ['title', 'description', 'created_at', 'updated_at'])
            ])->all()
        ];

        $fileName = time() . '_bookmarks.geojson';
        $fileStorePath = public_path('/download/map_bookmarks/'.$fileName);
        File::put($fileStorePath, json_encode($data));

        return response()->download($fileStorePath);
    }

    public function import(Request $request){
        if(!auth()->check()) abort(403);

        $request->validate([
            'file' => 'required|mimes:json',
        ]);

        $features = data_get(json_decode($request->file('file')->getContent(), true), 'features', []);

        collect($features)->each(function ($feature){
            $model = new MapBookmark();
            $model->fill([
                'title' => data_get($feature, 'properties.title'),
                'description' => data_get($feature, 'properties.description'),
                'geometry' => $feature['geometry']
            ])->save();
        });

        return [
            'status' => 'OK'
        ];
    }
}
