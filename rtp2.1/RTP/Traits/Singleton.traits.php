<?php
/**
 * Singleton（单例模式），用于创建单例对象
 * @author rolealiu/刘昊臻,www.rolealiu.com
 * @updateDate 20151227
 */

namespace RTP\Traits;

trait Singleton
{
	private static $instance;

	/**
	 * 创建对象
	 */
	protected abstract function __construct();

	/**
	 * 销毁对象
	 */
	protected abstract function __destruct();

	/**
	 * 获取实例
	 */
	public static function getInstance()
	{
		//如果已经含有一个实例则直接返回实例
		if (!is_null(self::$instance))
		{
			return self::$instance;
		}
		else
		{
			//如果没有实例则新建
			return self::getNewInstance();
		}
	}

	/**
	 * 获取一个新的实例
	 */
	public static function getNewInstance()
	{
		self::$instance = null;
		self::$instance = new self;
		return self::$instance;
	}

}
?>
