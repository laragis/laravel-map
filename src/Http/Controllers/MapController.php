<?php

namespace TungTT\LaravelMap\Http\Controllers;

use App\Http\Controllers\Controller;
use TungTT\LaravelMap\MenuItemTypes\LayerGroupMenuItemType;
use TungTT\LaravelMap\MenuItemTypes\LayerMenuItemType;
use TungTT\LaravelMap\Models\MapMap;
use TungTT\LaravelMap\Models\MapApi;
use TungTT\LaravelMap\Models\MapLayer;
use Illuminate\Http\Request;
use Outl1ne\MenuBuilder\MenuBuilder;

class MapController extends Controller
{
    public function embed(Request $request){
        $baseLayer = MapLayer::find($request->input('baselayerId'));

        $layers = MapLayer::whereIn('id', $request->input('layerIds'))->get();

        return [
            'baselayer' => $this->formatLayerItem($baseLayer),
            'layers' => $layers->map([$this, 'formatLayerItem'])
        ];
    }

    public function builder($id = null){
        $locale = array_keys(MenuBuilder::getLocales())[0];

        $map = MapMap::with(['baseLayerMenu', 'layerMenu']);
        $map = ($id ? $map->where('id', $id) : $map)->first();

        $baseLayerMenuItems = data_get($map->baseLayerMenu->formatForAPI($locale), 'menuItems')->pluck('value');
        $layerMenuItems = data_get($map->layerMenu->formatForAPI($locale), 'menuItems');

        $baseLayers = MapLayer::whereIn('id', $baseLayerMenuItems)->get()->map(fn($v) => $this->formatLayerItem($v))->toArray();


        return [
            'app' => [
                'siteName' => env('APP_NAME'),
                'siteIcon' => '',
            ],
            'layout' => [
                'side_panel' => [
                    'menu' => ['intro', 'doc', 'faq', 'feedback']
                ]
            ],
            'map' => [
                'map' => [
                    'id' => $map->id
                ],
                'config' => [
                    'baselayerId' => $map->baselayer_selected,
                    'center' => [$map->center_y, $map->center_x],
                    'zoom' => $map->zoom,
                ],
                'baselayers' => $baseLayers,
                'layers' => $this->getLayers($layerMenuItems),
                'search' => [
                    'provider' => $map->search_provider,
                    'apis' => MapApi::whereIn('id', $map->search_apis)->get()
                ]
            ],
            'search' => [
                'providerId' => $map->search_provider,
                'apis' => MapApi::whereIn('id', $map->search_apis)->get(['id', 'title', 'name'])
            ]
        ];
    }

    public function index(){
        return MapMap::all();
    }

//    protected function toLayerType($type)
//    {
//        return strtolower($type);
//    }
//
//    protected function exceptFieds($popup){
//        $pu = $popup->toArray();
//        return array_merge($popup->toArray(), [
//            'heading' => '{{phanloai_cl}}',
//            'fields' => collect($pu['fields'])->whereNotIn('attribute', ['yeuto_dt', 'hoten'])->all()
//        ]);
//    }
//
    protected function formatPopup($popup)
    {
        if(!$popup || ($popup && data_get($popup, 'enabled') !== true)) return null;
//
//        if (is_array($popup) && $popup['type'] === 'table' && !$popup['heading']) $popup['heading'] = $layer->name;
//
//        if(in_array($layer->id, [16, 24, 26]) && auth()->guest()) return $this->exceptFieds($popup);

        return $popup;
    }
//
//    protected function formatOptions($options){
//        if(isset($options['tiled'])) $options['tiled'] = !($options['tiled'] === 'false');
//        if(isset($options['zIndex'])) $options['zIndex'] = intval($options['zIndex']);
//
//        return $options;
//    }

    protected function getLayers($menuItems)
    {
        $mapDeep = function ($items, $fn) {
            return collect($items)->where('enabled', true)->map(function ($i, $data) use ($fn) {
                $data = [
                    'title' => $i['name']
                ];

                if ($i['type'] === LayerGroupMenuItemType::getIdentifier()) $data = array_merge($data, $i['data']);

                if ($i['value'] && $i['type'] == LayerMenuItemType::getIdentifier()) {
                    $layer = MapLayer::with(['service'])->find($i['value']);

                    if($i['name']) $layer->title = $i['name'];
                    $data = array_merge($data , $this->formatLayerItem($layer), ['icon' => false], $i['data']);
                }

                $api = MapApi::whereJsonContains('layers_id', $i['value'])->first();
                if($api && $api->status){
                    $data['api_url'] = '/api/restify/'.$api->name;
                }

                if ($i['children']->isNotEmpty()) {
                    $data['folder'] = true;
                    $data['children'] = $fn($i['children'], $fn)->all();
                };

                return $data;
            });
        };

        return $mapDeep($menuItems, $mapDeep);
    }


    public function formatLayerItem($layer){
        $service = $layer->service;
        $url = $layer->service ? $service->base_url : $layer->url;

        $defaultType = ($layer->group === 'baselayer') ? 'xyz' : 'wms';

        $ComponentProps = $layer->layer_params;

        $data = [
            'id' => $layer->id,
            'title' => $layer->title ?: 'None',
            'type' => $layer->type ?: $defaultType,
            'ComponentProps' => $ComponentProps
        ];

        if($service && $service->proxy_base) $data['proxy_base'] = $service->proxy_base;
        if($layer->opacity) $data['ComponentProps']['opacity'] = floatval($layer->opacity);
        if($url) $data['ComponentProps']['url'] = $url;
        if($layer->popup) $data['popup'] = $this->formatPopup($layer->popup);

        return $data;
    }
}
