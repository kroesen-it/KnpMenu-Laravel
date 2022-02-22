<?php

namespace Dowilcox\KnpMenu\Renderer;

use Knp\Menu\ItemInterface;
use Knp\Menu\Renderer\ListRenderer as BaseListRenderer;

class BootstrapRenderer extends BaseListRenderer
{


    public function render(ItemInterface $item, array $options = []): string
    {
        $options = \array_merge($this->defaultOptions, $options);

        $item->setAttribute('class', 'navbar ' . $item->getAttribute('class') ?? '');

        $html = "<nav {$this->renderHtmlAttributes($item->getAttributes())}>";

        $html .= $this->renderBrand($item);

        $uniqueId = substr(md5(mt_rand()), 0, 7);

        $html .= "<button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#$uniqueId' aria-controls='$uniqueId' aria-expanded='false' aria-label='Toggle navigation'>\n";
        $html .= "<span class='navbar-toggler-icon'></span>\n";
        $html .= "</button>\n";


        $html .= "<div class='collapse navbar-collapse' id='$uniqueId'>\n";
        $attributes = $item->getChildrenAttributes();
        $attributes['class'] = ($attributes['class'] ?? '') . ' navbar-nav';
        $html .= $this->renderList($item, $attributes, $options);
        $html .= "</div>\n";

        $html .= $this->renderText($item);

        $html .= "</nav>\n";

        if ($options['clear_matcher']) {
            $this->matcher->clear();
        }

        return $html;
    }

    /**
     * @param array<string, string|bool|null> $attributes
     * @param array<string, mixed>            $options
     */
    protected function renderList(ItemInterface $item, array $attributes, array $options): string
    {
        /*
         * Return an empty string if any of the following are true:
         *   a) The menu has no children eligible to be displayed
         *   b) The depth is 0
         *   c) This menu item has been explicitly set to hide its children
         */
        if (0 === $options['depth'] || !$item->hasChildren() || !$item->getDisplayChildren()) {
            return '';
        }

        $html = $this->format('<ul'.$this->renderHtmlAttributes($attributes).'>', 'ul', $item->getLevel(), $options);
        $html .= $this->renderChildren($item, $options);
        $html .= $this->format('</ul>', 'ul', $item->getLevel(), $options);

        return $html;
    }

    /**
     * Called by the parent menu item to render this menu.
     *
     * This renders the li tag to fit into the parent ul as well as its
     * own nested ul tag if this menu item has children
     *
     * @param array<string, mixed> $options The options to render the item
     */
    protected function renderItem(ItemInterface $item, array $options): string
    {
        // if we don't have access or this item is marked to not be shown
        if (!$item->isDisplayed()) {
            return '';
        }

        // create an array than can be imploded as a class list
        $class = (array) $item->getAttribute('class');

        $class[] = 'nav-item';

        if ($this->matcher->isCurrent($item)) {
            $class[] = $options['currentClass'];
        } elseif ($this->matcher->isAncestor($item, $options['matchingDepth'])) {
            $class[] = $options['ancestorClass'];
        }

        if ($item->actsLikeFirst()) {
            $class[] = $options['firstClass'];
        }
        if ($item->actsLikeLast()) {
            $class[] = $options['lastClass'];
        }

        if (0 !== $options['depth'] && $item->hasChildren()) {
            if (null !== $options['branch_class'] && $item->getDisplayChildren()) {
                $class[] = $options['branch_class'];
            }
        } elseif (null !== $options['leaf_class']) {
            $class[] = $options['leaf_class'];
        }

        // retrieve the attributes and put the final class string back on it
        $attributes = $item->getAttributes();
        if (!empty($class)) {
            $attributes['class'] = \implode(' ', $class);
        }


        // opening li tag
        $html = $this->format('<li'.$this->renderHtmlAttributes($attributes).'>', 'li', $item->getLevel(), $options);

        // render the text/link inside the li tag
        //$html .= $this->format($item->getUri() ? $item->renderLink() : $item->renderLabel(), 'link', $item->getLevel());
        $html .= $this->renderLink($item, $options);
        $html .= $this->renderElement($item, $options);

        // renders the embedded ul
        $childrenClass = (array) $item->getChildrenAttribute('class');
        $childrenClass[] = 'menu_level_'.$item->getLevel();

        $childrenAttributes = $item->getChildrenAttributes();
        $childrenAttributes['class'] = \implode(' ', $childrenClass);

        $html .= $this->renderList($item, $childrenAttributes, $options);

        // closing li tag
        $html .= $this->format('</li>', 'li', $item->getLevel(), $options);





        return $html;
    }

    /**
     * @param array<string, mixed> $options
     */
    protected function renderLinkElement(ItemInterface $item, array $options): string
    {
        \assert(null !== $item->getUri());

        $attributes = $item->getLinkAttributes();
        $attributes['class'] = ($attributes['class'] ?? '') . ' nav-link';
        return \sprintf('<a href="%s"%s>%s</a>', $this->escape($item->getUri()), $this->renderHtmlAttributes($attributes), $this->renderLabel($item, $options));
    }

    protected function renderElement(ItemInterface $item, array $options): string
    {
        if(!$item->getExtra('html')){
            return '';
        }
        $text = $item->getExtra('html');

        return $this->format($text, 'link', $item->getLevel(), $options);
    }

    protected function renderBrand(ItemInterface $item): string
    {
        $label = $item->getAttribute('brand') ?? null;
        if($item->getAttribute('image')){
            $alt = $label ?? 'Logo';
            return "<div class='navbar-brand'><img src='{$item->getAttribute('image')}' alt='$alt'/></div>\n";
        }
        if($label){
            return "<div class='navbar-brand'>{$label}</div>\n";
        }
        return '';
    }

    protected function renderText(ItemInterface $item, $before = false): string
    {
        $type = 'text-' . ($before ? 'before' : 'after');
        if(!$item->getExtra($type)){
            return '';
        }

        return "<span class='navbar-text'>{$item->getExtra($type)}</span>";

    }

}
