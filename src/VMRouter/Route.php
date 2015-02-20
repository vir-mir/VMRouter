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
	 * @var mixed
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
		$config['controller'] = array_key_exists('controller', $config) ? $config['controller'] : '';
		$config['domains'] = array_key_exists('domains', $config) ? $config['domains'] : ['*'];
		$this->config = $config;
	}

	/**
	 * Выходные параметры Route
	 *
	 * @return mixed
	 */
	public function getOutput()
	{
		return $this->output;
	}

	/**
	 * Разрешенные домены Route
	 *
	 * @return mixed
	 */
	public function getDomains()
	{
		return $this->config['domains'];
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

		$this->output = $instance->$action[1]();

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