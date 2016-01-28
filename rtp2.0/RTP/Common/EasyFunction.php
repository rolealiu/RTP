<?php
/**
 * 快速开发函数库
 * @author rolealiu/刘昊臻,www.rolealiu.com
 * @updateDate 20151229
 */

use RTP\Module as M;

/**
 * 快捷Cookie操作函数:Cookie
 */
function C($name, $value, $expire = 0, $secure = FALSE, $isHttOnly = FALSE)
{
	setcookie($name, $name, $expire, '', '', $secure, $isHttOnly);
}

/**
 * 快捷Dao操作函数:dao
 */
function D($isNewInstance = false)
{
	return $isNewInstance ? M\DatabaseModule::getNewInstance() : M\DatabaseModule::getInstance();
}

/**
 * 快捷退出函数:error
 */
function E($errorInfo, $position = NULL, $line = NULL)
{
	if (DEBUG)
	{
		if (is_null($position))
			exit("sorry! you have an error : <strong>$errorInfo</strong>");
		else
		if (is_null($line))
			exit("sorry! you have an error in <strong>$position</strong> : <strong>$errorInfo</strong>");
		else
			exit("sorry! you have an error in <strong>$position</strong> line <strong>$line</strong> : <strong>$errorInfo</strong>");

	}
	else
	{
		exit(0);
	}
}

/**
 * 快捷完成请求函数:Flush，用于一次性按顺序返回所有信息，无须担心Cookie放置位置。
 * 注意，需要配合C()函数以及P()函数使用
 */

function F()
{
	ob_start();
	$outputFlush = M\OutputStorageModule::getAll();
	if (is_null($outputFlush))
		return;
	foreach ($outputFlush as $value)
	{
		echo $value;
	}
	ob_end_flush();
	M\OutputStorageModule::clean();
}

/**
 * 快捷Header函数，发送特定的HTTP header信息
 */
function H($headerInfo = NULL, $statusCode = 200)
{
	//如果header已经发送过
	if (headers_sent())
		return;

	//如果状态码不为200（OK）并且头部信息为空
	if ($statusCode != 200 && is_null($headerInfo))
		http_response_code($statusCode);
	else
	{
		$header = "HTTP/1.1 $statusCode $headerInfo";
		header($header);
	}
}

/**
 * 快捷输入函数:input
 */
function I()
{
	$at = strtolower(AT);
	if (func_num_args() == 0)
	{
		switch ($at)
		{
			case 'auto' :
			{
				if (!empty($_GET))
				{
					foreach ($_GET as $key => $value)
					{
						$input[$key] = cleanFormate($value);
					}
					break;
				}
				else
				if (!empty($_POST))
				{
					foreach ($_POST as $key => $value)
					{
						$input[$key] = cleanFormate($value);
					}
					break;
				}
				break;
			}
			case 'post' :
			{
				foreach ($_POST as $key => $value)
				{
					$input[$key] = cleanFormate($value);
				}
				break;
			}
			case 'get' :
			{
				foreach ($_GET as $key => $value)
				{
					$input[$key] = cleanFormate($value);
				}
				break;
			}
			default :
				return null;
		}
		unset($key);
		unset($value);
		return $input;
	}
	else
	if (func_num_args() == 1)
	{
		$paramName = func_get_arg(0);
		switch ($at)
		{
			case 'auto' :
			{
				if (!empty($_GET))
				{
					return cleanFormate($_GET[$paramName]);
				}
				else
				if (!empty($_POST))
				{
					return cleanFormate($_POST[$paramName]);
				}
			}
			case 'post' :
			{
				return cleanFormate($_POST[$paramName]);
			}
			case 'get' :
			{
				return cleanFormate($_GET[$paramName]);
			}
			default :
				return null;
		}
	}
	else
	{
		$args = func_get_args();
		switch ($at)
		{
			case 'auto' :
			{
				if (!empty($_GET))
				{
					foreach ($args as &$paramName)
					{
						$input[$paramName] = cleanFormate($_GET[$paramName]);
					}
				}
				else
				if (!empty($_POST))
				{
					foreach ($args as &$paramName)
					{
						$input[$paramName] = cleanFormate($_POST[$paramName]);
					}
				}
				break;
			}
			case 'post' :
			{
				foreach ($args as &$paramName)
				{
					$input[$paramName] = cleanFormate($_POST[$paramName]);
				}
				break;
			}
			case 'get' :
			{
				foreach ($args as &$paramName)
				{
					$input[$paramName] = cleanFormate($_GET[$paramName]);
				}
				break;
			}
			default :
				return null;
		}
		unset($paramName);
		return $input;
	}
}

/**
 * 快捷流程函数:next,用于Controller=>Module=>Dao三层之间的快速执行同名方法
 */
function N()
{
	if (func_num_args() != 0)
	{
		$args = func_get_args();
		$target = strtolower($args[0]);
		if ($target == 'm' || $target == 'module')
			$args[0] = 'Module';
		else
		if ($target == 'd' || $target == 'dao')
			$args[0] = 'Dao';
		else
			$args[0] = NULL;

	}
	if (func_num_args() == 0 || is_null(func_get_arg(0)))
	{
		E('function N() has no target');
	}
	M\AutomaticallyModule::nextTo($args);
}

/**
 * 快捷输出函数:output,默认数组输出json,字符串直接输出
 */
function O($output)
{
	echo is_array($output) ? json_encode($output) : $output;
}

/**
 * 快捷序列化输出函数:print，需要配合F()函数使用
 */
function P($output, $distinct = FALSE)
{
	if ($distinct)
		if (M\OutputStorageModule::isExist($output))
			return;
	M\OutputStorageModule::set($output);
}

/**
 * 快速引入文件函数:require
 */
function R($filePath)
{
	$filePaths = array();
	if (!isset($filePaths[$filePath]))
	{
		if (is_file($filePath))
		{
			//require不使用函数形式是因为参数带括号会降低运行速度
			require $filePath;
			$filePaths[$filePath] = TRUE;
		}
		else
		{
			$filePaths[$filePath] = FALSE;
		}
	}
}

/**
 * 快捷Session操作函数:session
 */
function S($key, $value)
{
	if (session_status() == 1)
		session_start();
	if (isset($_SESSION[$key]))
	{
		if (isset($value))
			$_SESSION[$key] = &$value;
		return $_SESSION[$key];
	}
	else
		$_SESSION[$key] = &$value;
}

/**
 * 格式清除函数
 */
function cleanFormate(&$value)
{
	return htmlspecialchars(stripcslashes(trim($value)));
}
?>
