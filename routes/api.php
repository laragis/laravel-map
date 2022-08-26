<?php

Route::get('api/geonode/layers', function (){
   return \TungTT\LaravelMap\Models\GeoNode\Layer::pluck('title_en', 'name')->sort()->map(fn($label, $value) => [
       'label' => $label,
       'value' => $value,
   ])->values();
});