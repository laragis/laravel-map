<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use TungTT\LaravelMap\Http\Controllers\MapController;
use TungTT\LaravelMap\Http\Controllers\ShareController;

Route::get('api/map', [MapController::class, 'index']);
//Route::post('map/builder/embed', [MapController::class, 'embed']);
//Route::get('api/map/builder/{id?}', [MapController::class, 'builder']);

Route::get('/api/map/builder/{id?}', [MapController::class, 'builder']);

Route::get('/api/maps_share/{id}', [ShareController::class, 'show'])->middleware('web');
Route::post('/api/maps_share', [ShareController::class, 'store'])->middleware('web');


Route::get('/maps/share/{token}', [ShareController::class, 'show']);
Route::get('/maps/view', [MapController::class, 'view']);

Route::get('/maps/{path?}', function (){
    return File::get('apps/maps/index.html');
})
//    ->where('path', '[a-zA-Z0-9-/\:]+')
    ->where('path', '.+');

