# VM PHP Router class

Обработка маршрутов 

* настраиваемые HTTP методы
* REST - методы
* Можно раскладывать по группам

# Установка через **composer**

```javascript
{
    "require": {
        "vir-mir/router": "dev-master"
    }
}
```

## Описание

### Настройка URL

настройка для .htaccess

```apache
Options +FollowSymLinks
RewriteEngine On
RewriteRule ^(.*)$ index.php [NC,L]
```

## Пример php реализации

```php
<?php
require __DIR__.'/vendor/autoload.php';

use VMRouter\RouteCollection;
use VMRouter\Router;
use VMRouter\Route;

$collection = new RouteCollection();
$collection->attach(new Route('/users/', array(
    '_controller' => 'Controller\User::usersCreateAction',
    'methods' => 'POST'
)));

$collection->attach(new Route('/users/(?P<user_id>\d+)/', array(
    '_controller' => 'Controller\User::getUAction',
    'methods' => 'GET'
)));

$router = new Router($collection);
$route = $router->matchCurrentRequest();

var_dump($route);
```

## Загрузка из папки

```php
<?php
// index.php

require __DIR__.'/vendor/autoload.php';

use VMRouter\Router;
use VMRouter\RouteCollection;

$router = new Router(new RouteCollection());

$route = $router
	->setRoutesDir(__DIR__ . '/router/') // путь до папки с Routes
	->setRoutes()
	->matchCurrentRequest();


var_dump($route);
```

```php
<?php
// router/urers.php

use VMRouter\Route;

$routes = [];


array_push($routes, new Route('/users/', array(
			'_controller' => 'someController::users_create',
			'methods' => 'GET'
		)));

array_push($routes, new Route('/users/(?P<user_id>\d+)/cover/', array(
			'_controller' => 'someController::users_create',
			'methods' => 'GET'
		)));

array_push($routes, new Route('/users/(?P<user_id>\d+)/', array(
			'_controller' => 'someController::users_create',
			'methods' => 'GET'
		)));


return $routes;
```

