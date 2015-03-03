<?php namespace Dowilcox\KnpMenu;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Knp\Menu\Matcher\Matcher;
use Knp\Menu\Matcher\Voter\UriVoter;
use Knp\Menu\MenuFactory;
use Knp\Menu\Renderer\ListRenderer;

class MenuServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/menu.php' => config_path('menu.php')
        ]);
    }

    public function register()
    {
        $this->app->singleton('Dowilcox\KnpMenu\Menu', function ($app) {
            $renderOptions = $app['config']['menu.render'];
            $url = $app['url'];

            $collection = new Collection();

            $factory = new MenuFactory();

            $matcher = new Matcher();
            $matcher->addVoter(new UriVoter($url->current()));
            $matcher->addVoter(new UriVoter($url->full()));

            $renderer = new ListRenderer($matcher);

            return new Menu($renderOptions, $collection, $factory, $matcher, $renderer);
        });
    }

}