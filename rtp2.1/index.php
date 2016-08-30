<?php
/**
 * RIPPLE TECHNOLOGY PHP FRAMEWORK(RTP) 2.0
 * 此文件为统一入口，所有的请求将发送到此入口，并且由框架的路由机制进行分发
 * 详细的参数请参看框架起始文件
 * @author rolealiu/刘昊臻
 * @version 2.1 beta1
 * @updateDate 20160719
 */

//是否初次部署，设定为TRUE将在所有用户自行创建的用户目录下新建空白的index.html文件防止部分服务器开启的目录查看功能，上线前设为false提高性能
define('FIRST_DEPLOYMENT', TRUE);

//定义请求方式(AJAX-Type)，GET/POST/AUTO,默认为POST
define('AT', 'AUTO');

//是否开启纠错模式，开启之后将会输出所有错误信息，请在上线之前禁用DEBUG!
define('DEBUG', TRUE);

//主机地址
define('DB_URL', 'localhost');

//连接数据库的用户名
define('DB_USER', 'root');

//连接数据库的密码，推荐使用随机生成的字符串
define('DB_PASSWORD', 'root');

//数据库类型，用于PDO数据库连接
define('DB_TYPE', 'mysql');

//数据库名
define('DB_NAME', 'rtp');

//数据库是否需要保持长期连接（长连接）,多线程高并发环境下请开启,默认关闭
define('DB_PERSISTENT_CONNECTION', FALSE);

//框架存放的相对路径（相对于入口文件而言）,默认是'./RTP'
define('PATH_FW', './RTP');

//项目代码存放的相对路径（相对于入口文件而言）,默认是'./'
define('PATH_APP', './Server');

//引入框架
require PATH_FW . '/rtp.inc.php';
?>