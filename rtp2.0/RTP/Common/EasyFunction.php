<?php
/**
 * 快速开发函数库
 * @author rolealiu/刘昊臻,www.rolealiu.com
 * @updateDate 20160227
 */

use RTP\Module as M;

$filePaths = NULL;

/**
 * 快捷数据库操作函数
 */
function getDatabase($isNewInstance = false)
{
	return $isNewInstance ? M\DatabaseModule::getNewInstance() : M\DatabaseModule::getInstance();
}

/**
 * 快捷完成请求函数，用于一次性按顺序返回所有信息，无须担心Cookie放置位置。
 * 注意，需要配合P()函数使用
 */
function quickFlush()
{
	ob_start();
	$outputFlush = M\OutputStorageModule::getAll();
	if (is_null($outputFlush))
		return;
	foreach ($outputFlush as $value)
	{
		echo $value;
	}
	//输出缓冲区并且清除缓冲区内容
	ob_end_flush();
	M\OutputStorageModule::clean();
}

/**
 * 快捷输入函数
 */
function getInput()
{
	if (func_num_args() == 0)
	{
		//判断请求方式
		switch (strtolower(AT))
		{
			case 'auto' :
			{
				if (!empty($_GET))
				{
					foreach ($_GET as $key => $value)
					{
						$input[$key] = cleanFormat($value);
					}
					break;
				}
				else
				if (!empty($_POST))
				{
					foreach ($_POST as $key => $value)
					{
						$input[$key] = cleanFormat($value);
					}
					break;
				}
				break;
			}
			case 'post' :
			{
				foreach ($_POST as $key => $value)
				{
					$input[$key] = cleanFormat($value);
				}
				break;
			}
			case 'get' :
			{
				foreach ($_GET as $key => $value)
				{
					$input[$key] = cleanFormat($value);
				}
				break;
			}
			default :
				return NULL;
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
					return cleanFormat($_GET[$paramName]);
				}
				else
				if (!empty($_POST))
				{
					return cleanFormat($_POST[$paramName]);
				}
			}
			case 'post' :
			{
				return cleanFormat($_POST[$paramName]);
			}
			case 'get' :
			{
				return cleanFormat($_GET[$paramName]);
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
						$input[$paramName] = cleanFormat($_GET[$paramName]);
					}
				}
				else
				if (!empty($_POST))
				{
					foreach ($args as &$paramName)
					{
						$input[$paramName] = cleanFormat($_POST[$paramName]);
					}
				}
				break;
			}
			case 'post' :
			{
				foreach ($args as &$paramName)
				{
					$input[$paramName] = cleanFormat($_POST[$paramName]);
				}
				break;
			}
			case 'get' :
			{
				foreach ($args as &$paramName)
				{
					$input[$paramName] = cleanFormat($_GET[$paramName]);
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
 * 快捷输出函数:output,默认数组输出json,字符串直接输出
 */
function quickOutput($output)
{
	echo is_array($output) ? json_encode($output) : $output;
}

/**
 * 快捷序列化输出函数，需要配合quickFlush()函数使用
 */
function serialPrint($output, $distinct = FALSE)
{
	if ($distinct)
		if (M\OutputStorageModule::isExist($output))
			return;
	M\OutputStorageModule::set($output);
}

/**
 * 快速引入文件函数
 */
function quickRequire($filePath)
{
	global $filePaths;
	if (is_null($filePaths))
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
function quickSession($key, &$value)
{
	if (session_status() == 1)
		session_start();
	if (isset($_SESSION[$key]))
	{
		if (isset($value))
			$_SESSION[$key] = $value;
		return $_SESSION[$key];
	}
	else
		$_SESSION[$key] = $value;
}

/**
 * 格式清除函数
 */
function cleanFormat(&$value)
{
	return htmlspecialchars(stripcslashes(trim($value)));
}

/**
 * 换行输出数组信息
 */
function printFormatted(array $info)
{
	foreach ($info as $key => $value)
	{
		echo "$key:$value</br>";
	};
}
?>
