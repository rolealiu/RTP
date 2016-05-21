<?php
/**
 * 文件处理模块，用于框架的各种文件以及目录相关操作
 * @author rolealiu/刘昊臻,www.rolealiu.com
 * @updateDate 20151229
 */

namespace RTP\Module;

class FileModule
{
	/**
	 * 自动在用户目录下面创建空白index.html文件，用于保护文件目录
	 */
	public static function createSecurityIndex()
	{
		$path = PATH_APP;
		$dirs = array();
		$ban_dirs = array(
			'./',
			'.',
			'../',
			'..'
		);
		self::getAllDirs($path, $dirs, $ban_dirs);

		foreach ($dirs as $dir)
		{
			echo $dir.'</br>';
			if (file_exists($dir . '/index.html') || file_exists($dir . '/index.php'))
				continue;
			else
			{
				$file = fopen($dir . '/index.html', 'w');
				fwrite($file, '');
				fclose($file);
			}
		}
	}

	/**
	 * 获取路径下的所有目录
	 * @param String $path 目标路径
	 * @param array $dirs 用于储存返回路径的数组
	 * @param array $ban_dirs [可选]需要过滤的目录的相对地址的数组
	 */
	public static function getAllDirs($path, array &$dirs, array &$ban_dirs = array())
	{
		$paths = scandir($path);
		foreach ($paths as $nextPath)
		{
			if (!in_array($nextPath, $ban_dirs) && is_dir($path . DIRECTORY_SEPARATOR . $nextPath))
			{
				$dirs[] = realpath($path . DIRECTORY_SEPARATOR . urlencode($nextPath));
				self::getAllDirs($path . DIRECTORY_SEPARATOR . $nextPath, $dirs, $ban_dirs);
			}
		}
	}

	/**
	 * 获取路径下的所有文件
	 * @param String $path 目标路径
	 * @param array $dirs 用于储存返回路径的数组
	 * @param array $ban_dirs [可选]需要过滤的文件名的数组
	 */
	public static function getAllFiles($path, &$dirs, &$ban_dirs = array())
	{
		$paths = scandir($path);
		foreach ($paths as $nextPath)
		{
			if (!in_array($nextPath, $ban_dirs) && is_file($path . DIRECTORY_SEPARATOR . $nextPath))
			{
				$dirs[] = realpath($path . DIRECTORY_SEPARATOR . urlencode($nextPath));
				self::getAllFiles($path . DIRECTORY_SEPARATOR . $nextPath, $dirs, $ban_dirs);
			}
		}
	}

}
?>