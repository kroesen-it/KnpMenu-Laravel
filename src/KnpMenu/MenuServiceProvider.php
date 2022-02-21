<?php

namespace Dowilcox\KnpMenu;

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

        if(is_array(config('menu.menu'))){
            foreach (config('menu.menu') as $menu){
                (new $menu)();
            }
        }
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

}
