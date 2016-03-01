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

	public function login()
	{
	}

}
?>