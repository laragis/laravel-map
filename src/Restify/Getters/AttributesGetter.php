<?php
namespace TungTT\LaravelMap\Restify\Getters;

use Binaryk\LaravelRestify\Getters\Getter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use TungTT\LaravelMap\Models\GeoNode\Attribute;
use TungTT\LaravelMap\Models\MapApi;

class AttributesGetter extends Getter
{
    public static $uriKey = 'attributes';

    protected string $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function handle(Request $request): JsonResponse
    {
        $fillable_fields = data_get(MapApi::firstWhere('name', $this->type), 'fillable_fields', []);

        $attributes = Attribute::whereRelation('layer', 'name', $this->type)
            ->get()
            ->filter(function ($item) use($fillable_fields){
                return in_array($item->attribute, $fillable_fields);
            })
        ;

        return response()->json([
            'data' => $attributes->values()
        ]);
    }
}