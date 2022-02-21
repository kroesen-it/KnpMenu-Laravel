<?php


namespace Dowilcox\KnpMenu\Middleware;


use Closure;

class MenuRegisterer
{

    public function handle($request, Closure $next)
    {

        if(is_array(config('menu.menu'))){
            foreach (config('menu.menu') as $menu){
                (new $menu)();
            }
        }

        return $next($request);
    }
}
