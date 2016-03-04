<?php
/**
 * 异常处理模块
 * @author rolealiu/刘昊臻,www.rolealiu.com
 * @updateDate 20160301
 */
namespace RTP\Module;
class ExceptionModule
{

	/**
	 * 构造方法，传递错误信息以及错误码
	 */
	public function __construct($code = 10000, $info)
	{
		//如果记录文件夹不存在则新建
		if (!file_exists('./log/'))
		{
			//如果新建失败则抛出异常，可能权限不足
			if (!mkdir('./log/'))
			{
				//此处不用ExceptionModule进行异常抛出，因为如果权限不足，此异常会无限抛出进入死循环
				throw new Exception("can not create directory,please check you app's root file system authorization", 13001);
			}
		}

		//判断异常类型并返回
		switch (floor($code/1000))
		{
			//默认异常类型
			case 10 :
			{
				return new CommException($code, $info);
				break;
			}
			//路由异常
			case 11 :
			{
				return new RouteException($code, $info);
				break;
			}
			//数据库异常
			case 12 :
			{
				return new DatabaseException($code, $info);
				break;
			}
			//文件系统异常
			case 13 :
			{
				return new FileException($code, $info);
				break;
			}
			//数据异常
			case 14 :
			{
				return new DataException($code, $info);
				break;
			}

			default :
			{
				break;
			}
		}
		return;
	}

}

class FileException extends \Exception
{
	private $errorInfo;
	private $errorCode;

	/**
	 * 构造方法，传递错误信息以及错误码
	 */
	public function __construct($code,$info)
	{
		echo 123;
		$this -> info = $code;
		$this -> code = $code;
	}

	/**
	 * 输出错误信息
	 */
	public function printError($isStop = FALSE)
	{
		echo 'ok';
		//如果非调试模式，则取消所有的错误输出
		if (!DEBUG)
		{
			$infoJson = array('errorCode' => $this -> errorCode, );

			//输出json
			echo json_encode($infoJson);
		}
		else
		{
			$infoJson = array(
				'datetime' => date('Y/M/d H:i:s', time()),
				'errorCode' => $this -> errorCode,
				'info' => $this -> errorInfo,
				'wrongFile' => $this -> getFile(),
				'wrongLine' => $this -> getLine()
			);

			//输出自然语言
			printFormatted($infoJson);

			$logInfo = "{$infoJson['datetime']}=>[code:{$infoJson['errorCode']};info:{$infoJson['info']};wrongFile:{$infoJson['wrongFile']};wrongLine:{$infoJson['wrongLine']}];\n";

			//文件内容追加
			file_put_contents('./log/' . date('Y_M_d', time()) . '.txt', $logInfo, FILE_APPEND);
		}

		if ($isStop)
			exit ;

	}

}
?>