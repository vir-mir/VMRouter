<?php
/**
 * Created by PhpStorm.
 * User: vir-mir
 * Date: 13.02.15
 * Time: 15:41
 */

namespace VMRouter;


/**
 * @author vir-mir <virmir49@gmail.com>
 *
 * Class RouteCollection
 * @package VMRouter
 */
class RouteCollection extends \SplObjectStorage
{

    /**
     * Добовляем Route в колекцию
     *
     * @param Route $attachObject
     * @param null $data
     */
	public function attach($attachObject, $data = null)
	{
		parent::attach($attachObject, $data);
	}

	/**
	 * Возвращаем Route из колекции
	 *
	 * @return Route[]
	 */
	public function all()
	{
		$routes = [];
		foreach ($this as $route)
		{
			array_push($routes, $route);
		}

		return $routes;
	}

} 
