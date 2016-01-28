<?php
class UserDao
{
	//注意此处不是使用了命名空间，而是引入了traits,rtp框架通过traits来实现类似于多继承的机制，可以同时引入多个框架的特性
	use RTP\Traits\Observer;

	protected function before()
	{
		echo "我在Dao层初始化时先执行</br>";
	}

	protected function after()
	{
		echo "</br>我在Dao层销毁前先执行";
	}

	public function update()
	{

	}

	public function login()
	{
		/**
		 * 与登陆有关的操作
		 */
		$db = D();
		O($db -> query('select * from user where _id =1'));
	}

}
?>