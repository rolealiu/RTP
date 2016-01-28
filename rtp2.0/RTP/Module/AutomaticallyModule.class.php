<?php
/**
 * 自动化模块,用于路由分发请求,自动加载类,自动实例化对象,自动执行方法等
 * @author rolealiu/刘昊臻,www.rolealiu.com
 * @updateDate 20151211
 */

namespace RTP\Module;

Class AutomaticallyModule
{
	private static $path;
	private static $groupName;
	private static $controllerName;
	private static $moduleName;
	private static $operationName;
	private static $daoName;
	private static $modelName;

	//cut points
	private static $aopTarget = array(
		'UserController' => 'UserAspect:checkLogin',
		'UserController:login' => 'UserAspect:test'
	);

	public static function start()
	{
		//注册自动载入方法
		spl_autoload_register('self::autoloadUserController');
		spl_autoload_register('self::autoloadUserObserver');
		spl_autoload_register('self::autoloadUserModule');
		spl_autoload_register('self::autoloadUserDao');
		spl_autoload_register('self::autoloadRTPTraits');
		spl_autoload_register('self::autoloadRTPModule');
		spl_autoload_register('self::autoloadUserModel');

		//判断是否有PATH_INFO信息，如果没有则无需路由
		if (!isset($_SERVER['PATH_INFO']))
		{
			return;
		}

		//将PATH_INFO分割获取参数值
		self::$path = explode('/', substr($_SERVER['PATH_INFO'], 1));

		//组名
		self::$groupName = &self::$path[0];
		//控制器/模块名
		self::$controllerName = &self::$path[1];
		self::$moduleName = &self::$path[1];
		//操作名
		self::$operationName = &self::$path[2];

		if (!isset(self::$groupName))
		{
			E('undefined group param');
		}
		else
		if (!isset(self::$controllerName))
		{
			E('undefined module param');
		}
		else
		if (!isset(self::$operationName))
		{
			E('undefined operation param');
		}

		//实例化控制器对象
		$class = new \ReflectionClass(self::$controllerName . DIR_CONTROLLER);

		//系统魔术方法
		$php_magic_methods = array(
			'__construct',
			'__destruct',
			'__call',
			'__callstatic',
			'__get',
			'__set',
			'__isset',
			'__unset',
			'__sleep',
			'__wakeup',
			'__tostring',
			'__invoke',
			'__set_state',
			'__clone',
			'__debugInfo'
		);
		//如果拥有相应的操作方法,并且这些方法并不是php的魔术方法
		if (!in_array(strtolower(self::$operationName), $php_magic_methods) && $class -> hasMethod(self::$operationName))
		{
			//获取方法
			$method = $class -> getMethod(self::$operationName);
			//判断是否是公用方法
			if ($method -> isPublic())
			{
				//判断是否是静态方法，静态与非静态的执行操作有所不同
				if ($method -> isStatic())
				{
					$method -> invoke(NULL);
				}
				else
				{
					$method -> invoke($class -> newInstance());
				}
			}
			else
			{
				//非法操作
				E('illegal operation');
			}
		}
		else
		{
			//操作未定义
			E('undefined operation');
		}
	}

	/**
	 * 自动载入用户自定义控制器
	 */
	public static function autoloadUserController($className)
	{
		$path = realpath(PATH_APP . DIRECTORY_SEPARATOR . self::$groupName . DIRECTORY_SEPARATOR . DIR_CONTROLLER . DIRECTORY_SEPARATOR . self::$controllerName . DIR_CONTROLLER . '.class.php');
		R($path);
		//当控制器完成路由之后，取消自动载入控制器的路由，加快模块的加载速度
		spl_autoload_unregister('self::autoloadUserController');
	}

	/**
	 * 自动载入用户自定义观察者
	 */
	public static function autoloadUserObserver($className)
	{
		$path = realpath(PATH_APP . DIRECTORY_SEPARATOR . self::$groupName . DIRECTORY_SEPARATOR . DIR_OBSERVER . DIRECTORY_SEPARATOR . $className . '.class.php');
		R($path);
	}

	/**
	 * 自动载入用户自定义模块
	 */
	public static function autoloadUserModule($className)
	{
		$path = realpath(PATH_APP . DIRECTORY_SEPARATOR . self::$groupName . DIRECTORY_SEPARATOR . DIR_MODULE . DIRECTORY_SEPARATOR . $className . '.class.php');
		R($path);
	}

	/**
	 * 自动载入用户自定义Dao
	 */
	public static function autoloadUserDao($className)
	{
		$path = realpath(PATH_APP . DIRECTORY_SEPARATOR . self::$groupName . DIRECTORY_SEPARATOR . DIR_DAO . DIRECTORY_SEPARATOR . $className . '.class.php');
		R($path);
	}

	/**
	 * 自动载入用户自定义模型
	 */
	public static function autoloadUserModel($className)
	{
		$path = realpath(PATH_APP . DIRECTORY_SEPARATOR . self::$groupName . DIRECTORY_SEPARATOR . DIR_MODEL . DIRECTORY_SEPARATOR . $className . '.class.php');
		R($path);
	}

	/**
	 * 自动载入框架模块
	 */
	public static function autoloadRTPModule($className)
	{
		$path = realpath(PATH_FW . PATH_MODULE) . DIRECTORY_SEPARATOR . str_replace('RTP\Module\\', '', $className) . '.class.php';
		R($path);
	}

	/**
	 * 自动载入框架特性
	 */
	public static function autoloadRTPTraits($className)
	{
		$path = realpath(PATH_FW . PATH_TRAITS) . DIRECTORY_SEPARATOR . str_replace('RTP\Traits\\', '', $className) . '.traits.php';
		//		echo '</br>'.$path;
		R($path);
	}

	/**
	 * 快速执行方法，用于Controller=>Module=>Dao三层之间的快速执行同名方法
	 */
	public static function nextTo()
	{
		$args = func_get_args();
		$args = &$args[0];
		$argsNum = count($args);
		$className = self::$moduleName . $args[0];
		$class = new \ReflectionClass($className);
		if ($class -> hasMethod(self::$operationName))
		{
			$method = $class -> getMethod(self::$operationName);
			if ($method -> isPublic())
			{
				if ($argsNum == 1)
				{
					if ($method -> isStatic())
					{
						return $method -> invoke(NULL);
					}
					else
					{
						return $method -> invoke($class -> newInstance());
					}
				}
				else
				{
					unset($args[0]);
					if ($method -> isStatic())
					{
						$method -> invokeArgs(NULL, $args);
					}
					else
					{
						$method -> invokeArgs($class -> newInstance(), $args);
					}
				}
			}
			else
			{
				E('illegal operation name ' . self::$operationName . ' in function N()', $className);
			}
		}
	}

}
?>