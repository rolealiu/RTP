<?php
/**
 * Observer（观察者模式）
 * @author rolealiu/刘昊臻,www.rolealiu.com
 * @updateDate 20151228
 */

namespace RTP\Traits;

trait Observer
{
	protected $observers = NULL;

	/**
	 * 前置方法：对象创建后执行
	 */
	protected abstract function before();

	/**
	 * 后置方法：对象销毁前执行
	 */
	protected abstract function after();

	/**
	 * 更新方法：观察者被通知时从内部调用的方法
	 */
	public abstract function update($params = NULL);

	public function __construct()
	{
		$this -> before();
	}

	public function __destruct()
	{
		$this -> after();
	}

	/**
	 * 给特定的方法添加观察者
	 */
	protected function addObserver($currentMethodName, $observer)
	{
		//去除类名
		$currentMethodName = str_replace(__CLASS__ . '::', '', $currentMethodName);

		//如果对象储存器数组为空
		if (is_null($this -> observers))
			$this -> observers = array();

		//如果对象储存器为空
		if (!isset($this -> observers[$currentMethodName]))
			$this -> observers[$currentMethodName] = new \SplObjectStorage;

		$this -> observers[$currentMethodName] -> attach($observer);
	}

	/**
	 * 从特定的方法删除观察者
	 */
	protected function delObserver($currentMethodName, $observer)
	{
		//去除类名
		$currentMethodName = str_replace(__CLASS__ . '::', '', $currentMethodName);

		//如果对象储存器数组为空
		if (is_null($this -> observers) || !isset($this -> observers[$currentMethodName]))
			return;

		$this -> observers[$currentMethodName] -> detach($observer);
	}

	/**
	 * 通知注册在特定方法上面的观察者,调用观察者内部的update()方法
	 */
	protected function notifyObserver($targetMethodName, $params = null)
	{
		//去除类名
		$targetMethodName = str_replace(__CLASS__ . '::', '', $targetMethodName);

		foreach ($this->observers[$targetMethodName] as $observer)
		{
			$observer -> update($params);
		}
	}

}
?>
