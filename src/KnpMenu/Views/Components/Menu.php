<?php

namespace Dowilcox\KnpMenu\Views\Components;

use Closure;
use Dowilcox\KnpMenu\Exceptions\MenuNotFoundException;
use Dowilcox\KnpMenu\Facades\Menu as MenuBuilder;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Menu extends Component
{

    private string $name;

    public function __construct(string $name)
    {

        $this->name = $name;
    }

    /**
     * @throws MenuNotFoundException
     */
    public function render(): View|Htmlable|string|Closure
    {
        if(!MenuBuilder::has($this->name)){
            throw new MenuNotFoundException($this->name);
        }
        return MenuBuilder::render(MenuBuilder::get($this->name));
    }
}
