<?php
/**
 * 全局注册表模块，用于储存全局访问对象
 * @author rolealiu/刘昊臻,www.rolealiu.com
 * @updateDate 20151229
 */

namespace RTP\Module;

Class RegistryModule
{
	use \RTP\Traits\Singleton;

	private static $registry = NULL;

	protected function __construct()
	{
		if (is_null(self::$registry))
			self::$registry = array();
	}

	/**
	 * 获取全局变量
	 */
	public static function get($name)
	{
		if (isset(self::$registry[$name]))
			return self::$registry[$name];
		return NULL;
	}

	/**
	 * 设置全局变量
	 */
	public static function set($name, $value)
	{
		if (is_null(self::$registry))
			self::$registry = array();
		self::$registry[$name] = $value;
	}

	/**
	 * 删除全局变量
	 */
	public static function del($name)
	{
		unset(self::$registry[$name]);
	}

	/**
	 * 将数组输入全局变量
	 */
	public static function setArray($array, $overWrite = FALSE)
	{
		while ($kv = each($array))
		{
			//如果已经存在重复的键,则覆盖之前的值
			if ($overWrite && isset(self::$registry[$kv[0]]))
				self::$registry[$kv[0]] = $kv[1];
			//否则跳过相同的键
			else
			if (isset(self::$registry[$kv[0]]))
				continue;
			else
				self::$registry[$kv[0]] = $kv[1];
		}
	}

	/**
	 * 获取全局变量数组
	 */
	public static function getAll()
	{
		return self::$registry;
	}

	/**
	 * 清除所有全局变量
	 */
	public static function clean()
	{
		unset(self::$registry);
		self::$registry = NULL;
	}

}
?>