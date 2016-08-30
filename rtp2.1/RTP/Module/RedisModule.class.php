<?php
/**
 * Redis模块，用于创建Redis单例链接
 * @author rolealiu/刘昊臻,www.rolealiu.com
 * @updateTime 20160719
 */

namespace RTP\Module;

class RedisModule
{
	private static $redis_con;

	/**
	 * 销毁对象时自动断开数据库连接
	 */
	function __destruct()
	{
		self::close();
	}

	/**
	 * 获取实例
	 */
	public static function getInstance()
	{
		//如果已经含有一个实例则直接返回实例
		if (!is_null(self::$redis_con))
		{
			return self::$redis_con;
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
		self::$redis_con = NULL;

		//尝试连接数据库
		self::$redis_con = new \Redis;
		//使用pconnect长连接
		if (!self::$redis_con -> pconnect(REDIS_URL, REDIS_PORT))
			throw new ExceptionModule(15001, 'Redis connect fail,please check redis host and port.');
		if (!self::$redis_con -> auth(REDIS_REQUIREPASS))
			throw new ExceptionModule(15002, 'Wrong Redis requirepass.');

		return self::$redis_con;
	}

	/**
	 * 关闭主机连接
	 */
	public function close()
	{
		self::$redis_con -> close();
		self::$redis_con = NULL;
	}

}
?>
