<?php

namespace CrudDev\CrudDev\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use File;

class GenerateModelWithCustomFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->ask('What is your model name?');

        if ($this->confirm('Do you want to add controller?')) {
            $this->controller($name);
        }

        if ($this->confirm('Do you want to add blade?')) {
            $this->blade($name);
        }

        $this->model($name="Test");
    }

    protected function getStub($type)
    {
//        $filePath = __DIR__.'/../../../resources/stubs/'.$type.'.stub'; //another way get file
//        $filePath = resource_path("stubs/$type.stub"); // after published
        $filePath = base_path('vendor/crud-dev/crud-dev/src/resources/stubs/'.$type.'.stub');

        if (file_exists(base_path("stubs/$type.stub"))) {
            return file_get_contents(base_path("stubs/$type.stub"));
        } else {
            return file_get_contents($filePath);
        }
    }

    protected function blade($name)
    {
        $bladeName = strtolower(Str::plural($name));
        $controllerName = ucfirst($name);
        $route = Str::plural(strtolower($name));
        $modelTemplate = str_replace(
            ['{{modelNamePluralLowerCase}}'],
            [$bladeName],
            $this->getStub('blade')
        );
        file_put_contents(resource_path("views/{$bladeName}.blade.php"), $modelTemplate);
        File::append(
            base_path('routes/web.php'),
            str_replace(
                'controllerName',
                '',
                'Route::get("' . $route . '",[App\Http\Controllers\controllerName' . $controllerName . 'Controller::class, "view"]);'
            )
        );
    }

    protected function model($name)
    {
        $name = ucfirst($name);
        $modelTemplate = str_replace(
            ['{{ class }}', '{{ namespace }}'],
            [$name."Model", "App\Models"],
            $this->getStub('model')
        );

        file_put_contents(app_path("/Models/{$name}Model.php"), $modelTemplate);
    }


    protected function controller($name)
    {
        $controllerName = ucfirst($name);
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}'
            ],
            [
                $controllerName,
                strtolower(Str::plural($name)),
                strtolower($name)
            ],
            $this->getStub('controller')
        );

        $route = Str::plural(strtolower($name));
        File::append(
            base_path('routes/api.php'),
            str_replace(
                'controllerName',
                '',
                'Route::resource("' . $route . '",App\Http\Controllers\controllerName' . $controllerName . 'Controller::class);'
            )
        );

        file_put_contents(app_path("/Http/Controllers/{$controllerName}Controller.php"), $controllerTemplate);
    }
}
