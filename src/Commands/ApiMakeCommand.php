<?php

namespace TungTT\LaravelMap\Commands;

use App\Restify\Repository;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use TungTT\LaravelMap\Models\GeoModel;
use TungTT\LaravelMap\Restify\GeoRepository;

// sail artisan map-api:make BaubangGiaithua --connection=geonode_data --table-name=baubang_giaithua -a
// sail artisan map-api:make \\App\\Models\\GeonodeData\\BaubangGiaithua --connection=geonode_data --table-name=baubang_giaithua --base-model-class=Model --scout -a
// sail artisan map-api:make \\App\\Models\\GeonodeData\\BaubangGiaithua --connection=geonode_data --table-name=baubang_giaithua --base-model-class=\\App\\Models\\GeonodeData\\GeoModel --base-repository-class=GeoRepository --scout -a

class ApiMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'map-api:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Map API';

    protected $stubName = null;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->option('all')) {
            $this->input->setOption('model', true);
            $this->input->setOption('repository', true);
            $this->input->setOption('policy', true);
        }

        if ($this->option('model')) {
            $this->createModel();
        }

        if ($this->option('repository')) {
            $this->createRepository();
        }

        if ($this->option('policy')) {
            $this->createPolicy();
        }
    }

    protected function createModel(){
        $this->stubName = 'model';

        $name = $this->qualifyModel($this->getNameInput());
        $path = $this->getPath($name);

        $this->makeDirectory($path);

        $content = $this->files->get($this->getStub());

        $baseRepoClass = $this->option('base-model-class');

        $replacements = [
            '{{namespace}}' => $this->getNamespace($name),
            '{{class}}' => $fileName = $this->getModelName(),
            '{{baseClass}}' => Str::contains($baseRepoClass, '\\') ? "\\".$baseRepoClass : $baseRepoClass,
            '{{scout}}' => $this->option('scout') ? 'use \TungTT\LaravelMap\Models\Concerns\ApiSearchable;' : '',
            '{{connection}}' => $this->getConnection(),
            '{{table}}' => $this->getTable(),
            '{{primaryKey}}' => $this->getPrimaryKeyTable(),

            '{{fillable}}' => collect($this->option('fillable'))->map(fn($col) => "'{$col}'")->implode(', '),
            '{{search}}' => collect($this->option('search'))->map(fn($col) => "'{$col}'")->implode(', '),
            '{{display}}' => collect($this->option('display'))->map(fn($col) => "'{$col}'")->implode(', '),
        ];

        $content = str_replace(array_keys($replacements), array_values($replacements), $content);

        $this->files->put($path, $this->sortImports($content));

        $this->info("The [{$fileName}] model has been created.");

    }

    protected function createRepository(){
        $this->stubName = 'repository';

        $name = "App\\Restify\\Api\\{$this->getFileName()}Repository";
        $path = $this->getPath($name);

        $baseRepoClass = $this->option('base-repository-class');

        $replacements = [
            '{{namespace}}' => $this->getNamespace($name),
            '{{class}}' => $fileName = $this->getFileName($name),
            '{{baseClass}}' => Str::contains($baseRepoClass, '\\') ? "\\".$baseRepoClass : $baseRepoClass,
            '{{modelClass}}' => $modelClass = $this->qualifyModel($this->getNameInput()),
            '{{modelName}}' => $baseModelName = class_basename($modelClass),
            '{{uriKey}}' => Str::of($baseModelName)->kebab()->replace('-', '_'),
            '{{primaryKey}}' => $this->getPrimaryKeyTable(),
        ];

        $content = str_replace(array_keys($replacements), array_values($replacements), $this->getStubContent());

        $this->makeDirectory($path);
        $this->files->put($path, $this->sortImports($content));

        $this->info("The [{$fileName}] repository has been created.");
    }

    protected function createPolicy(){
        $this->stubName = 'policy';

        $name = "App\\Policies\\Api\\{$this->getFileName()}Policy";
        $path = $this->getPath($name);

        $replacements = [
            '{{namespace}}' => $this->getNamespace($name),
            '{{class}}' => $fileName = $this->getFileName($name),
            '{{modelClass}}' => $modelClass = $this->qualifyModel($this->getNameInput()),
            '{{modelName}}' => class_basename($modelClass),
        ];

        $content = str_replace(array_keys($replacements), array_values($replacements), $this->getStubContent());

        $this->makeDirectory($path);
        $this->files->put($path, $this->sortImports($content));

        $this->info("The [{$fileName}] repository has been created.");
    }

    protected function getFileName($name = null){
        return Str::studly(class_basename($name ?? $this->getNameInput()));
    }

    protected function getStubContent(){
        return $this->files->get($this->getStub());
    }

    protected function getPrimaryKeyTable(){
        $query = "SELECT constraint_name, table_name, column_name, ordinal_position FROM information_schema.key_column_usage WHERE table_name = '{$this->getTable()}'";
        return data_get(\DB::connection($this->getConnection())->selectOne($query), 'column_name') ?? 'id';
    }

    protected function getConnection(){
        return $this->option('connection') ?? 'default';
    }

    protected function getTable(){
        return $this->option('table-name') ?? Str::of($this->getModelName())->kebab()->replace('-', '_')->toString();
    }

    protected function getTableColumns(){
        return Schema::connection($this->getConnection())->getColumnListing($this->getTable());
    }

    protected function getModelClass(){
        return $this->qualifyModel($this->getNameInput());
    }

    protected function getModelName(){
        return class_basename($this->getModelClass());
    }

    protected function getGeometryCollumns(){

    }

    protected function getModelFillable(){
        $columns  = collect($this->getTableColumns())
            ->reject(fn($col) => $col === $this->getPrimaryKeyTable())
            ->map(fn($col) => "'{$col}'");

        return "{$columns->implode(', ')}";
    }

     /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['all', 'a', InputOption::VALUE_NONE, 'Generate a model, repository, policy for the model'],
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the model already exists'],
            ['model', null, InputOption::VALUE_NONE, 'Create a new model'],
            ['repository', null, InputOption::VALUE_NONE, 'Create a new repository'],
            ['policy', null, InputOption::VALUE_NONE, 'Create a new policy'],
            ['policy', null, InputOption::VALUE_NONE, 'Create a new policy'],
            ['table-name', 'tn', InputOption::VALUE_OPTIONAL, 'Name of the table to use', null],
            ['connection', 'cn', InputOption::VALUE_OPTIONAL, 'Connection property', 'default'],
            ['base-model-class', null, InputOption::VALUE_OPTIONAL, 'Model parent class', GeoModel::class],
            ['base-repository-class', null, InputOption::VALUE_OPTIONAL, 'Model parent class', GeoRepository::class],
            ['fillable', null, InputOption::VALUE_OPTIONAL, 'Fillable property'],
            ['display', null, InputOption::VALUE_OPTIONAL, 'Display property'],
            ['search', null, InputOption::VALUE_OPTIONAL, 'Search property'],
            ['scout', null, InputOption::VALUE_NONE, 'Use scout'],
        ];
    }

    protected function getStub()
    {
        return $this->resolveStubPath("/stubs/ttungbmt-stubs/{$this->stubName}.stub");
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/'))) ? $customPath : __DIR__.$stub;
    }
}
