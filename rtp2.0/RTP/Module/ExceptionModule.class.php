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
				return new Exception\FileException($code, $info);
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
				return new Exception($info, $code);
				break;
			}
		}
	}

}
?>