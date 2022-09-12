<?php

//Route::get('api/geonode/layers', function (){
//   return \TungTT\LaravelMap\Models\GeoNode\Layer::pluck('title_en', 'name')->sort()->map(fn($label, $value) => [
//       'label' => $label,
//       'value' => $value,
//   ])->values();
//});

use Illuminate\Support\Facades\Route;
use TungTT\LaravelMap\Http\Controllers\MapController;

Route::get('api/map', [MapController::class, 'index']);
//Route::post('map/builder/embed', [MapController::class, 'embed']);
//Route::get('api/map/builder/{id?}', [MapController::class, 'builder']);

Route::middleware('api')->group(function (){
    Route::get('/api/map/builder/{id?}', [MapController::class, 'builder'])->middleware(['auth:sanctum']);
});

Route::get('/maps/{path?}', function (){
    return File::get('apps/maps/index.html');
})->where('path', '[a-zA-Z0-9-/]+');
