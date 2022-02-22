<?php namespace Dowilcox\KnpMenu\Interfaces;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Knp\Menu\MenuFactory;
use Knp\Menu\Renderer\ListRenderer;

interface MenuInterface
{
    /**
     * Create a new menu
     */
    public function create(string $name, array $options = [], ?string $renderer = null): ItemInterface;

    /**
     * Get a menu by the name
     */
    public function get(string $name): ItemInterface;

    /**
     * Check if menu exists
     */
    public function has(string $name): bool;

    /**
     * Forget a menu
     */
    public function forget($name): void;

    /**
     * Render passed menu
     */
    public function render(ItemInterface $menu, array $options = []): string;

    /**
     * Get the factory instance
     */
    public function getFactory(): MenuFactory;

    /**
     * Get the matcher instance
     */
    public function getMatcher(): MatcherInterface;

    /**
     * Get the renderer instance
     */
    public function getRenderer(): ListRenderer;

}
