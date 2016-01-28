<?php
/**
 * 动态setter特性，用于对外部不可见的属性赋值
 * @author rolealiu/刘昊臻,www.rolealiu.com
 * @updateDate 20151227
 */

namespace RTP\Traits;

trait Setter
{
	/**
	 * 魔术方法，当用户尝试从外部对不可访问的属性赋值的时候会自动执行
	 */
	protected function __set($param)
	{
		return $this -> getter($param);
	}

	/**
	 * 当用户尝试从外部对不可访问的属性赋值的时候所需要执行的操作
	 */
	protected abstract function getter($params);
}
?>