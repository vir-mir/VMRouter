<?php
/**
 * Created by PhpStorm.
 * User: vir-mir
 * Date: 13.02.15
 * Time: 14:59
 */

namespace VMRouter;


class Route
{

	/**
	 * Url - из настроек
	 *
	 * @var string
	 */
	private $url;

	/**
	 * Допустимые HTTP методы
	 *
	 * @var string[]
	 */
	private $methods = ['GET', 'POST', 'PUT', 'DELETE'];

	/**
	 * Параметры прищедщие из URL
	 *
	 * @var array
	 */
	private $params;

	/**
	 * Параметры Route
	 *
	 * @var array
	 */
	private $config;

	/**
	 *  Выходные параметры Route
	 *
	 * @var array
	 */
	private $output;


	/**
	 * @param $resource
	 * 	- регулярука url
	 * @param array $config
	 * 	- настройки
	 */
	public function __construct($resource, array $config)
	{
		$this->url = $resource;
		$this->methods = array_key_exists('methods', $config) ? $config['methods'] : [];
		$config['_controller'] = array_key_exists('_controller', $config) ? $config['_controller'] : '';
		$this->config = $config;
	}

	/**
	 * Выходные параметры Route
	 * 
	 * @return array
	 */
	public function getOutput()
	{
		return (array)$this->output;
	}

	/**
	 * вызов контролера
	 *
	 * @throws \Exception
	 */
	public function dispatch()
	{
		$action = explode('::', $this->config['_controller']);
		if (!class_exists($action[0]))
		{
			throw new \Exception('Не существует такого контроллера');
		}

		$instance = new $action[0]($this->getParameters());

		if (!method_exists($instance, $action[1]))
		{
			throw new \Exception('Не существует такого метода в контроллере');
		}

		$instance->$action[1]();

	}


	/**
	 * допустимые методы для Route
	 *
	 * @return string[]
	 */
	public function getMethods()
	{
		return (array)$this->methods;
	}

	/**
	 * Шаблон URL
	 *
	 * @return string
	 */
	public function getRegex()
	{
		return (string)$this->url;
	}

	/**
	 * @param array $params
	 *
	 * @return $this
	 */
	public function setParameters(array $params)
	{
		$this->params = $params;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getParameters()
	{
		return $this->params;
	}

} 