<?php
/**
 * 动态getter特性，用于获取外部不可见的属性
 * @author rolealiu/刘昊臻,www.rolealiu.com
 * @updateDate 20151227
 */

namespace RTP\Traits;

trait Getter
{
	/**
	 * 魔术方法，当用户外部获取不可访问的属性的时候会自动执行
	 */
	protected function __get($param)
	{
		return $this -> getter($param);
	}

	/**
	 * 用户自行定义当从外部获取不可访问的属性的时候所需要执行的操作
	 */
	protected abstract function getter($params);
}
?>