<?php
/**
 * 异常类接口
 * @author rolealiu/刘昊臻,www.rolealiu.com
 * @updateDate 20160302
 */
namespace RTP\Implement;
interface RTPException extends \Exception
{
	private $errorInfo;
	private $errorCode;

	/**
	 * 构造方法，传递错误信息以及错误码
	 */
	public function __construct($info, $code = NULL);

	/**
	 * 实现输出错误的方法，所有的RTPException都会实现这一方法，并由ExceptionModule统一返回含有该方法的对象，由逻辑调用
	 */
	public function printError($isStop = FALSE);
}
?>