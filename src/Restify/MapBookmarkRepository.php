<?php

namespace TungTT\LaravelMap\Restify;

use App\Restify\Repository;
use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;
use TungTT\LaravelMap\Exports\MapBookmarkExport;
use TungTT\LaravelMap\Imports\MapBookmarkImport;
use TungTT\LaravelMap\Models\MapBookmark;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use File;

class MapBookmarkRepository extends Repository
{
    public static string $model = MapBookmark::class;

    public static string $uriKey = 'map_bookmark';

    public static array $sort = ['id', 'title'];

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
        $router->get('export', [static::class, 'export']);
        $router->post('import', [static::class, 'import']);
    }

    public function export()
    {
        $data = [
            'type' => 'FeatureCollection',
            'features' => static::$model::where('user_id', auth()->user()?->id)->get()->map(fn($model) => [
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
        Excel::import(new MapBookmarkImport, $request->file('file'));
        return [];
    }
}
