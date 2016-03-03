<?php
/**
 * 自动化模块,用于路由分发请求,自动加载类,自动实例化对象,自动执行方法等
 * @author rolealiu/刘昊臻,www.rolealiu.com
 * @updateDate 20160301
 */

namespace RTP\Module;

class AutomaticallyModule
{
	private static $path;
	private static $groupName;
	private static $controllerName;
	private static $moduleName;
	private static $operationName;
	private static $daoName;
	private static $modelName;

	public static function start()
	{
		//注册自动载入方法
		spl_autoload_register('self::autoloadUserController');
		spl_autoload_register('self::autoloadUserObserver');
		spl_autoload_register('self::autoloadUserModule');
		spl_autoload_register('self::autoloadUserDao');
		spl_autoload_register('self::autoloadRTPTraits');
		spl_autoload_register('self::autoloadRTPImplement');
		spl_autoload_register('self::autoloadRTPModule');
		spl_autoload_register('self::autoloadRTPException');

		//判断是否有PATH_INFO信息，如果没有则无需路由
		if (!isset($_SERVER['PATH_INFO']))
			throw new ExceptionModule(13001, 'url is lack of pathinfo');

		//将PATH_INFO分割获取参数值
		self::$path = explode('/', substr($_SERVER['PATH_INFO'], 1));

		//组名
		self::$groupName = &self::$path[0];
		//控制器/模块名
		self::$controllerName = &self::$path[1];
		self::$moduleName = &self::$path[1];
		//操作名
		self::$operationName = &self::$path[2];

		//检查pathinfo完整性
		if (!isset(self::$groupName))
			throw new ExceptionModule('error in lack of groupName', 11002);
		else
		if (!isset(self::$controllerName))
			throw new ExceptionModule('error in lack of controllerName', 11003);
		else
		if (!isset(self::$operationName))
			throw new ExceptionModule('error in lack of operationName', 11004);

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
				//操作无法访问
				throw new ExceptionModule('operation isn\'t a public function', 11005);
			}
		}
		else
		{
			//操作无法访问
			throw new ExceptionModule('undefined operation or illegal operation name', 11006);
		}
	}

	/**
	 * 自动载入用户自定义控制器
	 */
	public static function autoloadUserController($className)
	{
		$path = realpath(PATH_APP . DIRECTORY_SEPARATOR . self::$groupName . DIRECTORY_SEPARATOR . DIR_CONTROLLER . DIRECTORY_SEPARATOR . self::$controllerName . DIR_CONTROLLER . '.class.php');
		quickRequire($path);
		//当控制器完成路由之后，取消自动载入控制器的路由，加快模块的加载速度
		spl_autoload_unregister('self::autoloadUserController');
	}

	/**
	 * 自动载入用户自定义观察者
	 */
	public static function autoloadUserObserver($className)
	{
		$path = realpath(PATH_APP . DIRECTORY_SEPARATOR . self::$groupName . DIRECTORY_SEPARATOR . DIR_OBSERVER . DIRECTORY_SEPARATOR . $className . '.class.php');
		quickRequire($path);
	}

	/**
	 * 自动载入用户自定义模块
	 */
	public static function autoloadUserModule($className)
	{
		$path = realpath(PATH_APP . DIRECTORY_SEPARATOR . self::$groupName . DIRECTORY_SEPARATOR . DIR_MODULE . DIRECTORY_SEPARATOR . $className . '.class.php');
		quickRequire($path);
	}

	/**
	 * 自动载入用户自定义Dao
	 */
	public static function autoloadUserDao($className)
	{
		$path = realpath(PATH_APP . DIRECTORY_SEPARATOR . self::$groupName . DIRECTORY_SEPARATOR . DIR_DAO . DIRECTORY_SEPARATOR . $className . '.class.php');
		quickRequire($path);
	}

	/**
	 * 自动载入框架模块
	 */
	public static function autoloadRTPModule($className)
	{
		$path = realpath(PATH_FW . PATH_MODULE) . DIRECTORY_SEPARATOR . str_replace('RTP\Module\\', '', $className) . '.class.php';
		quickRequire($path);
	}

	/**
	 * 自动载入框架特性
	 */
	public static function autoloadRTPTraits($className)
	{
		$path = realpath(PATH_FW . PATH_TRAITS) . DIRECTORY_SEPARATOR . str_replace('RTP\Traits\\', '', $className) . '.traits.php';
		quickRequire($path);
	}

	/**
	 * 自动载入框架异常类
	 */
	public static function autoloadRTPException($className)
	{
		$path = realpath(PATH_FW . PATH_EXCEPTION) . DIRECTORY_SEPARATOR . str_replace('RTP\Module\RTPException\\', '', $className) . '.class.php';

		quickRequire($path);
	}

	/**
	 * 自动载入框架接口
	 */
	public static function autoloadRTPImplement($className)
	{
		$path = realpath(PATH_FW . PATH_IMPLEMENT) . DIRECTORY_SEPARATOR . str_replace('RTP\Implement\\', '', $className) . '.imple.php';
		quickRequire($path);
	}

}
?>