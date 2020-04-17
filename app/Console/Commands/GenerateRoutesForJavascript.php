<?php
/**
 * Routes Generator for Javascript
 * Reference :: https://ideas.hexbridge.com/how-to-use-laravel-routes-in-javascript-4d9c484a0d97
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\File;

class GenerateRoutesForJavascript extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'route:js';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate routes for javascript.';

    protected $router;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Router $router)
    {
        parent::__construct();

        $this->router = $router;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $routes = [];
        foreach ($this->router->getRoutes() as $route) {
            $routes[$route->getName()] = $route->uri();
        }

        if(!File::isDirectory('resources/js/config')){
            File::makeDirectory('resources/js/config');
        }
        File::put('resources/js/config/routes.json', json_encode($routes, JSON_PRETTY_PRINT));
    }
}
