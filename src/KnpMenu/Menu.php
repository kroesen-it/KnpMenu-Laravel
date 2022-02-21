<?php namespace Dowilcox\KnpMenu;

use Dowilcox\KnpMenu\Interfaces\MenuInterface;
use Illuminate\Support\Collection;
use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Knp\Menu\MenuFactory;
use Knp\Menu\Renderer\ListRenderer;

class Menu implements MenuInterface
{

    /**
     * Default render options.
     *
     */
    protected array $renderOptions = [];

    /**
     * Holds each instance of a menu.
     *
     * @var Collection
     */
    protected Collection $collection;

    /**
     * MenuFactory instance.
     *
     * @var MenuFactory
     */
    protected MenuFactory $factory;

    /**
     * Instance of a Matcher.
     *
     * @var MatcherInterface
     */
    protected MatcherInterface $matcher;

    /**
     * ListRenderer instance.
     *
     * @var ListRenderer
     */
    protected ListRenderer $renderer;

    /**
     * Class constructor.
     *
     * @param                  $renderOptions
     * @param Collection       $collection
     * @param MenuFactory      $factory
     * @param MatcherInterface $matcher
     * @param ListRenderer     $renderer
     */
    public function __construct(
        $renderOptions,
        Collection $collection,
        MenuFactory $factory,
        MatcherInterface $matcher,
        ListRenderer $renderer
    ) {
        $this->renderOptions = $renderOptions;
        $this->collection = $collection;
        $this->factory = $factory;
        $this->matcher = $matcher;
        $this->renderer = $renderer;
    }

    public function create(string $name, array $options = []): ItemInterface
    {
        $menu = $this->factory->createItem($name, $options);
        $this->collection->put($name, $menu);

        return $menu;
    }

    public function get($name): ItemInterface
    {
        return $this->collection->get($name);
    }

    public function has($name): bool
    {
        return $this->collection->has($name);
    }

    public function forget($name): void
    {
        $this->collection->forget($name);
    }

    public function render(ItemInterface $menu, array $options = []): string
    {
        $renderOptions = array_merge($this->renderOptions, $options);

        return $this->renderer->render($menu, $renderOptions);
    }

    public function getFactory(): MenuFactory
    {
        return $this->factory;
    }

    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

    public function getRenderer(): ListRenderer
    {
        return $this->renderer;
    }
}
