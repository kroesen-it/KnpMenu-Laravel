KnpMenu-Laravel
============
Laravel 8/9 package to create navigation menus, based on [KnpLabs/KnpMenu](https://github.com/KnpLabs/KnpMenu).

### Installation
Add to your composer.json file
```json
"dowilcox/knp-menu-laravel": "^2"
```

### Register the package

In config/app.php add the alias

```php
'aliases' => [
    ...
    'Menu' => Dowilcox\KnpMenu\Facades\Menu::class,
],
```

#### Publish config
```bash
php artisan vendor:publish
```

### Usage

Create menu
```php
<?php

namespace App\Menu;

use Dowilcox\KnpMenu\Facades\Menu;

class MainMenu
{

    public function __invoke()
    {
        $menu = Menu::create('main-menu', ['childrenAttributes' => ['class' => 'nav']]);
        $menu->addChild('Home', ['uri' => url('/')]);
        $menu->addChild('Users', ['uri' => route('admin.users.index')]);
        $menu->addChild('Roles', ['uri' => route('admin.roles.index')]);
        $menu->addChild('Menu', ['uri' => url('menu')]);
    }
}
```
Add menu in config: 
```php
    'menu' => [
        \App\Menu\MainMenu::class,
    ],
```
Add menu middleware (the middleware loads the registered menu's) in `app/Http/kernel.php` or to a specific route.

Call menu in Blade view:
```html
<x-knp::menu name="main-menu" />
```

This will output:
```html
<ul class="nav">
  <li class="first">
    <a href="http://localhost:8000">Home</a>
  </li>
  <li>
    <a href="http://localhost:8000/admin/users">Users</a>
  </li>
  <li>
    <a href="http://localhost:8000/admin/roles">Roles</a>
  </li>
  <li class="current active last">
    <a href="http://localhost:8000/menu">Menu</a>
  </li>
</ul>
```
### Configuration
You can change the renderer in the config:
```php
return [
    ...
    'renderer' => \Knp\Menu\Renderer\ListRenderer::class,
];
```
Or when creating the menu:
```php
$menu = Menu::create('main-menu',[], \Knp\Menu\Renderer\ListRenderer::class);
```

#### Renderers
* Bootstrap navbar v5: 
  * <code>[Dowilcox\KnpMenu\Renderer\BootstrapRenderer](src/KnpMenu/Renderer/BootstrapRenderer.php)</code>
* ListRenderer (default): 
  * <code>\Knp\Menu\Renderer\ListRenderer</code>
* Create your own
