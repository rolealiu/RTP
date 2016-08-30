<?php
class UserDao
{
	//注意此处不是使用了命名空间，而是引入了traits,rtp框架通过traits来实现类似于多继承的机制，可以同时引入多个框架的特性
	use RTP\Traits\Observer;

	protected function before()
	{
	}

	protected function after()
	{
	}

	public function update()
	{
	}

	public function login(&$testParam)
	{
		//这里存放数据库的操作
		//使用单例模式获取数据库实例
		//$db = getDatabase();
		//执行一条简单的操作，并且返回结果。有很多种数据库操作方法，可以看一下框架的Module里面的DatabaseModule
		//$result = $db->execute();
		return $testParam;
	}

}
?>