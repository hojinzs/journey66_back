<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{

    /**
     * @var mixed
     */
    private $domain;

    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * @var mixed
     */
    private $api_sub_domain;
    /**
     * @var mixed
     */
    private $admin_sub_domain;

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->api_sub_domain = env('APP_API_PREFIX');
        $this->admin_sub_domain = env('APP_ADMIN_PREFIX');
        $this->domain = env('APP_ROOT_DOMAIN');

        $this->mapApiRoutes();
        $this->mapWebRoutes();
        $this->mapAdminApiRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::domain($this->api_sub_domain.'.'.$this->domain)
            ->prefix('v1')
            ->name('front.api.')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    protected function mapAdminApiRoutes()
    {
        Route::domain($this->admin_sub_domain.'.'.$this->domain)
            ->prefix('api')
            ->name('admin.api.')
            ->namespace($this->namespace)
            ->group(base_path('routes/adminApi.php'));
    }
}
