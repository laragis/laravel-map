<?php
namespace TungTT\LaravelMap\Observers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use TungTT\LaravelMap\Models\MapApi;

class MapApiObserver
{
    public function created(MapApi $mapApi)
    {
        $this->save($mapApi);
    }

    public function updated(MapApi $mapApi)
    {
        $this->save($mapApi);
    }

    public function save(MapApi $model){
        if($model->isDirty('name')){
            Artisan::call('map-api:delete', [
                'name' => $model->getOriginal('model_type'),
            ]);
        }

        $connection = $model->connection ?? env('GEODATABASE_CONNECTION_NAME', 'default');

        Artisan::call('map-api:make', [
            'name' => $model->model_type,
            '--connection' => $connection,
            '--table-name' => $model->table,
            '--scout' => $model->scout,
            '--fillable' => $model->fillable_fields,
            '--search' => $model->search_fields,
            '--display' => $model->display_fields,
            '--all' => true,
        ]);

        if($model->scout){
            $modelClass = Str::replace('\\', '\\\\', $model->model_type);
            Artisan::call('scout:import '.$modelClass);
        } else {
            $this->deleteIndexByeAlias($model->name);
        }

    }

    protected function setAllIndexes(){
//        $contents = var_export(MapApi::pluck('model_type')->toArray(), true);
//
//        file_put_contents(config_path('explorer_indexes.php'), "<?php\n return {$contents};\n ");
    }

    public function deleted(MapApi $model)
    {
        Artisan::call('map-api:delete', [
            'name' => $model->model_type,
        ]);

        $this->deleteIndexByeAlias($model->name);
    }

    public function restored(MapApi $mapApi)
    {
        //
    }

    public function forceDeleted(MapApi $mapApi)
    {
        //
    }

    protected function deleteIndexByeAlias($alias){
//        Http::delete(env('ELASTICSEARCH_HOST').'/'.$alias)->json();

        $response = Http::get(env('ELASTICSEARCH_HOST').'/_aliases');
        $indexes = collect($response->json())->map(fn($i, $k) => array_keys($i['aliases']));

        foreach ($indexes as $index => $aliases){
            if(in_array($alias, $aliases)){
                Http::delete(env('ELASTICSEARCH_HOST').'/'.$index)->json();
            }
        }
    }
}