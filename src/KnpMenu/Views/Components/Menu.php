<?php

namespace Dowilcox\KnpMenu\Views\Components;

use Closure;
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

    public function render(): View|Htmlable|string|Closure
    {
        return MenuBuilder::render(MenuBuilder::get($this->name));
    }
}
