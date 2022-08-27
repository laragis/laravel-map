<?php

namespace TungTT\LaravelMap;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use TungTT\LaravelMap\Commands\ApiDeleteCommand;
use TungTT\LaravelMap\Commands\ApiMakeCommand;
use TungTT\LaravelMap\Commands\LaravelMapCommand;

class LaravelMapServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-map')
            ->hasConfigFile()
            ->hasViews()
            ->hasRoute('api')
            ->hasMigration('create_map_table')
            ->hasCommands([
                ApiMakeCommand::class,
                ApiDeleteCommand::class,
            ]);
    }

    public function packageBooted()
    {
        // Register Policies for APIs
        $fileManager = app(Filesystem::class);

        if($fileManager->exists(app_path('Models/Api'))){
            foreach (app(Filesystem::class)->files(app_path('Models/Api')) as $file){
                $name = pathinfo($file->getBasename(), PATHINFO_FILENAME);
                $model = "App\\Models\\Api\\{$name}";
                $policy = "App\\Policies\\Api\\{$name}Policy";

                if($this->exists($model) && $this->exists($policy)){
                    Gate::policy($model, $policy);
                }
            }
        }

    }

    protected function exists($rawName)
    {
        return app(Filesystem::class)->exists($this->getPath($rawName));
    }

    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->app->getNamespace(), '', $name);

        return $this->app['path'].'/'.str_replace('\\', '/', $name).'.php';
    }
}
