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
        $name = strtolower($this->ask('What is your model name?'));

        if ($this->confirm('Do you want to add controller?')) {
            $this->controller($name);
        }

        if ($this->confirm('Do you want to add view?')) {
            $this->view($name);
        }

        $this->model($name);
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
                '{{ namespace }}',
                '{{ rootNamespace }}',
                '{{ class }}'
            ],
            [
                "App\Http\Controllers",
                "App\\",
                $controllerName."Controller"
            ],
            $this->getStub('controller')
        );

//        $route = Str::plural(strtolower($name));
//        File::append(
//            base_path('routes/api.php'),
//            str_replace(
//                'controllerName',
//                '',
//                'Route::resource("' . $route . '",App\Http\Controllers\controllerName' . $controllerName . 'Controller::class);'
//            )
//        );

        file_put_contents(app_path("/Http/Controllers/{$controllerName}Controller.php"), $controllerTemplate);
    }

    protected function view($name)
    {
        $bladeName = strtolower(Str::plural($name));

        $modelTemplate = str_replace(
            ['{{ class }}'],
            [$bladeName],
            $this->getStub('view')
        );
        file_put_contents(resource_path("views/{$bladeName}.blade.php"), $modelTemplate);

//        $controllerName = ucfirst($name);
//        $route = Str::plural(strtolower($name));
//        File::append(
//            base_path('routes/web.php'),
//            str_replace(
//                'controllerName',
//                '',
//                'Route::get("' . $route . '",[App\Http\Controllers\controllerName' . $controllerName . 'Controller::class, "view"]);'
//            )
//        );
    }

    protected function getStub($type)
    {
//        $filePath = __DIR__.'/../../../resources/stubs/'.$type.'.stub'; //another way get file
//        $filePath = resource_path("views/vendor/crud-dev/$type.stub"); // after published
//        $filePath = file_exists(base_path("stubs/$type.stub")); // default stub path

        $filePath = base_path('vendor/crud-dev/crud-dev/src/resources/stubs/'.$type.'.stub');

        if (file_exists(resource_path("views/vendor/crud-dev/$type.stub"))) {
            return file_get_contents(resource_path("views/vendor/crud-dev/$type.stub"));
        } else {
            return file_get_contents($filePath);
        }
    }
}
