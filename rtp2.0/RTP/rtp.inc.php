<?php
/**
 * 框架起始文件，入口文件引入此文件来启用框架
 * @author rolealiu/刘昊臻,www.rolealiu.com
 * @version 2.0 beta3
 * @updateDate 20160301
 */

namespace RTP;
use RTP\Module;

//是否初次部署，设定为TRUE将在所有用户自行创建的用户目录下新建空白的index.html文件防止部分服务器开启的目录查看功能
defined('FIRST_DEPLOYMENT') or define('FIRST_DEPLOYMENT', FALSE);

//定义请求方式(AJAX-Type)，GET/POST/AUTO,默认为POST
defined('AT') or define('AT', 'AUTO');

//是否开启纠错模式，开启之后将会输出所有错误信息，请在上线之前禁用DEBUG!
defined('DEBUG') or define('DEBUG', TRUE);

//数据库类型，用于PDO数据库连接
defined('DB_TYPE') or define('DB_TYPE', 'mysql');

//主机地址
defined('DB_URL') or define('DB_URL', 'localhost');

//主机端口,默认mysql为3306
defined('DB_PORT') or define('DB_PORT', '3306');

//连接数据库的用户名
defined('DB_USER') or define('DB_USER', 'root');

//连接数据库的密码，推荐使用随机生成的字符串
defined('DB_PASSWORD') or define('DB_PASSWORD', 'root');

//数据库名
defined('DB_NAME') or define('DB_NAME', 'rpt');

//数据库是否需要保持长期连接（长连接）,多线程高并发环境下请开启,默认关闭
defined('DB_PERSISTENT_CONNECTION') or define('DB_PERSISTENT_CONNECTION', TRUE);

//框架模块目录名称
defined('PATH_MODULE') or define('PATH_MODULE', '/Module/');

//框架函数目录名称
defined('PATH_COMMON') or define('PATH_COMMON', '/Common/');

//框架特性(Traits)目录名称
defined('PATH_TRAITS') or define('PATH_TRAITS', '/Traits/');

//框架异常(Exception)目录名称
defined('PATH_EXCEPTION') or define('PATH_EXCEPTION', '/Module/Exception/');

//框架接口(Interface)目录名称
defined('PATH_IMPLEMENT') or define('PATH_IMPLEMENT', '/Inplement/');

//用户控制器目录名称
defined('DIR_CONTROLLER') or define('DIR_CONTROLLER', 'Controller');

//用户模块目录名称
defined('DIR_MODULE') or define('DIR_MODULE', 'Module');

//用户模块目录名称
defined('DIR_OBSERVER') or define('DIR_OBSERVER', 'Observer');

//用户Dao目录名称
defined('DIR_DAO') or define('DIR_DAO', 'Dao');

//用户数据模型目录名称
defined('DIR_MODEL') or define('DIR_MODEL', 'Model');

//框架存放的相对路径（相对于入口文件而言）,默认是'./RTP'
defined('PATH_FW') or define('PATH_FW', './RTP');

//项目代码存放的相对路径（相对于入口文件而言）,默认是'./'
defined('PATH_APP') or define('PATH_APP', './');

//设置时区
date_default_timezone_set('Asia/Shanghai');

//判断DEBUG模式操作
DEBUG ? error_reporting(E_ALL ^ E_NOTICE) : error_reporting(0);

//引入必要文件文件
require PATH_FW . PATH_COMMON . 'EasyFunction.php';
require PATH_FW . PATH_MODULE . 'AutomaticallyModule.class.php';

//启动自动化模块
try
{
	Module\AutomaticallyModule::start();

	//如果是首次部署项目，则在所有的项目下面新建空白的安全文件
	if (FIRST_DEPLOYMENT)
		Module\FileModule::createSecurityIndex();
}
catch(Module\ExceptionModule $e)
{
	$e -> printError();
}
catch(\Exception $e)
{
	if (DEBUG)
		print_r($e -> getMessage());
	exit ;
}
?>