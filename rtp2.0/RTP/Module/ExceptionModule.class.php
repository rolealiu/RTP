<?php
/**
 * 异常处理模块
 * @author rolealiu/刘昊臻,www.rolealiu.com
 * @updateDate 20160301
 */
namespace RTP\Module;
class ExceptionModule extends \Exception
{

	private $errorInfo;
	private $errorCode;

	/**
	 * 构造方法，传递错误信息以及错误码
	 */
	public function __construct($info, $code = NULL)
	{
		$this -> errorInfo = $info;
		$this -> errorCode = $code;
	}

	/**
	 * 输出错误信息
	 */
	public function printError($isStop = FALSE)
	{
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

			//如果记录文奸济恶不存在则新建
			if (!file_exists('./log/'))
			{
				//如果新建失败则抛出异常，可能权限不足
				if (!mkdir('./log/'))
					throw new ExceptionModule("Error Processing Request", 13001);
			}

			$logInfo = "{$infoJson['datetime']}=>[code:{$infoJson['errorCode']};info:{$infoJson['info']};wrongFile:{$infoJson['wrongFile']};wrongLine:{$infoJson['wrongLine']}];\n";

			//文件内容追加
			file_put_contents('./log/' . date('Y_M_d', time()) . '.txt', $logInfo, FILE_APPEND);
		}

		if ($isStop)
			exit ;

	}

}
?>