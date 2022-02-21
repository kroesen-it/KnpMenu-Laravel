<?php

namespace Dowilcox\KnpMenu;

use Dowilcox\KnpMenu\Middleware\MenuRegisterer;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Knp\Menu\Matcher\Matcher;
use Knp\Menu\Matcher\Voter\UriVoter;
use Knp\Menu\MenuFactory;
use Knp\Menu\Renderer\ListRenderer;

class MenuServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/menu.php' => config_path('menu.php'),
        ]);

        Blade::componentNamespace('Dowilcox\\KnpMenu\\Views\\Components', 'knp');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'knp');

        $this->registerMiddleware();
    }

    /**
     * Register application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/menu.php', 'menu');

        $this->app->singleton('menu', function ($app) {
            $renderOptions = $app['config']['menu.render'];
            $url = $app['url'];

            $collection = new Collection();

            $factory = new MenuFactory();

            $matcher = new Matcher([
                new UriVoter($url->current()),
                new UriVoter($url->full())
            ]);

            $renderer = new ListRenderer($matcher);

            return new Menu($renderOptions, $collection, $factory, $matcher, $renderer);
        });

        $this->app->bind('Dowilcox\KnpMenu\Menu', function ($app) {
            return $app['menu'];
        });
    }

    private function registerMiddleware()
    {
        /** @var Router $router */
        $router = $this->app->get(Router::class);

        // Add middleware alias
        $router->aliasMiddleware('menu', MenuRegisterer::class);

        // Add middleware menu to the web middleware group
        $middlewareGroups = $router->getMiddlewareGroups();
        $webMiddleware = $middlewareGroups['web'] ?? [];
        $webMiddleware[] = 'menu';
        $router->middlewareGroup('web', $webMiddleware);
    }
}
