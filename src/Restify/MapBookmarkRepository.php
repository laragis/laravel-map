<?php

namespace TungTT\LaravelMap\Restify;

use App\Restify\Repository;
use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Maatwebsite\Excel\Facades\Excel;
use TungTT\LaravelMap\Exports\MapBookmarkExport;
use TungTT\LaravelMap\Imports\MapBookmarkImport;
use TungTT\LaravelMap\Models\MapBookmark;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

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
        return (new MapBookmarkExport)->download(static::$uriKey.'.xls', \Maatwebsite\Excel\Excel::XLS);
    }

    public function import(Request $request){
        Excel::import(new MapBookmarkImport, $request->file('file'));
        return [];
    }
}
