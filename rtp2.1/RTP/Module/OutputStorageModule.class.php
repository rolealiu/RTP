<?php
/**
 * 全局输出缓存模块，用于储存输出缓存的内容,请勿用于其他数据的储存，防止数据混乱
 * @author rolealiu/刘昊臻,www.rolealiu.com
 * @updateDate 20160227
 */

namespace RTP\Module;

Class OutputStorageModule
{
	use \RTP\Traits\Singleton;

	/**
	 * 注册表变量
	 */
	private static $registry = NULL;

	/**
	 * 构造函数
	 */
	protected function __construct()
	{
		if (is_null(self::$registry))
			self::$registry = array();
	}

	/**
	 * 获取储存的数据
	 */
	public static function get($offset)
	{
		if (isset(self::$registry[$offset]))
			return self::$registry[$offset];
		return NULL;
	}

	/**
	 * 设置缓存数据值
	 */
	public static function set($value)
	{
		if (is_null(self::$registry))
			self::$registry = array();

		self::$registry[] = $value;
	}

	/**
	 * 获取所有缓存值
	 */
	public static function getAll()
	{
		return self::$registry;
	}

	/**
	 * 检查缓存值是否已经存在
	 */
	public static function isExist($value)
	{
		if (is_null(self::$registry))
		{
			return FALSE;
		}
		if (in_array($value, self::$registry))
		{
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * 清除缓存值
	 */
	public static function clean()
	{
		self::$registry = NULL;
	}

}
?>