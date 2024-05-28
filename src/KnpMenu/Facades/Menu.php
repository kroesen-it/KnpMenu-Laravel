<?php namespace Dowilcox\KnpMenu\Facades;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Facade;
use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Knp\Menu\MenuFactory;
use Knp\Menu\MenuItem;
use Knp\Menu\Renderer\ListRenderer;

/**
 * @method MenuItem create(string $name, array $options = [], ?string $renderer = null)
 * @method ItemInterface get(string $name)
 * @method bool has(string $name)
 * @method void forget($name)
 * @method Htmlable render(ItemInterface $menu, array $options = [])
 * @method MenuFactory getFactory()
 * @method MatcherInterface getMatcher()
 * @method ListRenderer getRenderer()
 */
class Menu extends Facade
{

    protected static function getFacadeAccessor(): string
    {
        return 'menu';
    }

}
