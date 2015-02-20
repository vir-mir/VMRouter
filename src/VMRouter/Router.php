<?php
/**
 * Created by PhpStorm.
 * User: vir-mir
 * Date: 13.02.15
 * Time: 15:40
 */

namespace VMRouter;


class Router {

	/**
	 * @var RouteCollection
	 */
	private $routes;

	/**
	 * Путь к папки с настройками Routes
	 *
	 * @var string
	 */
	private $routesDir;


	/**
	 * @param RouteCollection $collection
	 */
	public function __construct(RouteCollection $collection)
	{
		$this->routes = $collection;
	}

	/**
	 * Обрабатываем URL
	 *
	 * @return bool|Route
	 */
	public function matchCurrentRequest()
	{
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		$requestUrl = $_SERVER['REQUEST_URI'];

		// обрезаем GET параметры
		if (($pos = strpos($requestUrl, '?')) !== false)
		{
			$requestUrl =  substr($requestUrl, 0, $pos);
		}

		return $this->match($requestUrl, $requestMethod);
	}


	/**
	 * Обработка Url
	 *
	 * @param $requestUrl
	 * @param string $requestMethod
	 *
	 * @return bool|Route
	 */
	public function match($requestUrl, $requestMethod = "GET")
	{
		foreach ($this->routes->all() as $route)
		{
			if (!in_array($requestMethod, $route->getMethods()))
			{
				continue;
			}

            $domains = $route->getDomains();

            if (!is_array($domains))
            {
                continue;
            }

            if (!(in_array('*', $domains) || in_array($_SERVER['HTTP_HOST'], $domains)))
            {
                continue;
            }

			if (!preg_match("~^".$route->getRegex()."$~", $requestUrl, $matches))
			{
				continue;
			}

			$params = [];

			if ($matches)
			{
				foreach ($matches as $key => $val)
				{
					if (is_string($key))
					{
						$params[$key] = $val;
					}
				}
			}

			$route
				->setParameters($params)
				->dispatch();

			return $route;
		}

		return false;
	}

	/**
	 * Установка пути к папки с настройками Routes
	 *
	 * @param string $dir
	 *
	 * @return $this
	 */
	public function setRoutesDir($dir)
	{
		$this->routesDir = strval($dir);
		return $this;
	}

	/**
	 * Установка Routes из папки с настройками
	 *
	 * @return $this
	 */
	public function setRoutes()
	{
		if (!$this->routesDir || ($this->routesDir && !is_dir($this->routesDir)))
		{
			return $this;
		}

		$this->routesDir = rtrim($this->routesDir, '/');

		$files = glob($this->routesDir . '/*');

		if (!$files)
		{
			return $this;
		}

		foreach ($files as $file)
		{
			$routes = require $file;
			if ($routes && is_array($routes))
			{
				foreach ($routes as $route)
				{
					$this->routes->attach($route);
				}
			}
			elseif ($routes && $routes instanceof Route)
			{
				$this->routes->attach($routes);
			}
		}


		return $this;
	}

} 