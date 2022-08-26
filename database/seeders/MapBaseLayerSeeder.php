<?php

namespace TungTT\LaravelMap\Database\Seeders;

use TungTT\LaravelMap\Models\MapLayer;
use Illuminate\Database\Seeder;

class MapBaseLayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        sail artisan db:seed --class=\\TungTT\\LaravelMap\\Database\\Seeders\\MapBaseLayerSeeder

        $baselayers = [
            'Google Maps' => 'https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',
            'Google Satellite' => 'https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',
            'Google Satellite Hybrid' => 'https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}',
            'Google Terrain Hybrid' => 'https://mt1.google.com/vt/lyrs=p&x={x}&y={y}&z={z}',
            'Bing Virtual Earth' => 'https://ecn.t3.tiles.virtualearth.net/tiles/a{q}.jpeg?g=1',
            'Carto Antique' => 'https://cartocdn_a.global.ssl.fastly.net/base-antique/{z}/{x}/{y}.png',
            'Carto Dark' => 'https://a.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png',
            'Carto Eco' => 'https://cartocdn_a.global.ssl.fastly.net/base-eco/{z}/{x}/{y}.png',
            'Carto Light' => 'https://a.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.pn',
            'Esri Boundaries and Places' => 'https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}',
            'Esri Dark Gray' => 'https://server.arcgisonline.com/arcgis/rest/services/Canvas/World_Dark_Gray_Base/MapServer/tile/{z}/{y}/{x}',
            'ESri DeLorme' => 'https://server.arcgisonline.com/arcgis/rest/services/Specialty/DeLorme_World_Base_Map/MapServer/tile/{z}/{y}/{x}',
            'Esri Imagery' => 'https://server.arcgisonline.com/arcgis/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
            'Esri Light Gray' => 'https://server.arcgisonline.com/arcgis/rest/services/Canvas/World_Light_Gray_Base/MapServer/tile/{z}/{y}/{x}',
            'Esri National Geographic' => 'https://server.arcgisonline.com/arcgis/rest/services/NatGeo_World_Map/MapServer/tile/{z}/{y}/{x}',
            'Esri Ocean' => 'https://server.arcgisonline.com/arcgis/rest/services/Ocean_Basemap/MapServer/tile/{z}/{y}/{x}',
            'Esri Physical' => 'https://server.arcgisonline.com/arcgis/rest/services/World_Physical_Map/MapServer/tile/{z}/{y}/{x}',
            'Esri Shaded Relief' => 'https://server.arcgisonline.com/arcgis/rest/services/World_Shaded_Relief/MapServer/tile/{z}/{y}/{x}',
            'Esri Street' => 'https://server.arcgisonline.com/arcgis/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}',
            'Esri Terrain' => 'https://server.arcgisonline.com/arcgis/rest/services/World_Terrain_Base/MapServer/tile/{z}/{y}/{x}',
            'Esri Topographic' => 'https://server.arcgisonline.com/arcgis/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}',
            'F4 Map - 2D' => 'https://tile1.f4map.com/tiles/f4_2d/{z}/{x}/{y}.png',
            'Stamen Toner' => 'http://a.tile.stamen.com/toner/{z}/{x}/{y}.png',
            'Stamen Toner Background' => 'http://a.tile.stamen.com/toner-background/{z}/{x}/{y}.png',
            'Stamen Toner Hybrid' => 'http://a.tile.stamen.com/toner-hybrid/{z}/{x}/{y}.png',
            'Stamen Toner Lite' => 'http://a.tile.stamen.com/toner-lite/{z}/{x}/{y}.png',
            'Stamen Terrain' => 'http://a.tile.stamen.com/terrain/{z}/{x}/{y}.png',
            'Stamen Terrain Background' => 'http://a.tile.stamen.com/terrain-background/{z}/{x}/{y}.png',
            'Stamen Watercolor' => 'http://c.tile.stamen.com/watercolor/{z}/{x}/{y}.jpg',
            'Wikimedia Maps' => 'https://maps.wikimedia.org/osm-intl/{z}/{x}/{y}.png',
            'Vietbando Maps' => 'http://images.vietbando.com/ImageLoader/GetImage.ashx?Ver%3D2016%26LayerIds%3DVBD%26Y%3D%7By%7D%26X%3D%7Bx%7D%26Level%3D%7Bz%7D',
            'Vietnam OSM BecaMaps' => 'https://thuduc-maps.hcmgis.vn/thuducserver/gwc/service/wmts?layer=thuduc:thuduc_maps&style=&tilematrixset=EPSG:900913&Service=WMTS&Request=GetTile&Version=1.0.0&Format=image/png&TileMatrix=EPSG:900913:{z}&TileCol={x}&TileRow={y}',
        ];

        foreach ($baselayers as $title => $url){
            MapLayer::create([
                'type' => 'xyz',
                'url' => $url,
                'group' => 'baselayer',
                'title' => $title,
                'visibility' => true,
            ]);
        }
    }
}
