<?php

namespace TungTT\LaravelMap\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

// sail artisan map-api:delete \\App\\Models\\GeonodeData\\BaubangGiaithua

class ApiDeleteCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'map-api:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Map API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->qualifyModel($this->getNameInput());
        $baseName = Str::studly(class_basename($name));
        $path = $this->getPath($name);

        $files = [
            $name => $path,
            $repoName = "App\\Restify\\Api\\{$baseName}Repository" => $this->getPath($repoName),
            $policyName = "App\\Policies\\Api\\{$baseName}Policy" => $this->getPath($policyName),
        ];

        foreach ($files as $name => $path){
            if(file_exists($path)) {
                $this->files->delete($path);
                $this->info($name. " was deleted successfully");
            } else {
                $this->warn('Not found: '.$name);
            }
        }
    }

    protected function getStub()
    {

    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['all', 'a', InputOption::VALUE_NONE, 'Generate a migration, seeder, factory, policy, and resource controller for the model'],
        ];
    }
}
