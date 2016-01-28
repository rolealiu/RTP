<?php
class UserModule
{
	//注意此处不是使用了命名空间，而是引入了traits,rtp框架通过traits来实现类似于多继承的机制，可以同时引入多个框架的特性
	use RTP\Traits\Observer;

	protected function before()
	{
		echo "我在Module层初始化时先执行</br>";
	}

	protected function after()
	{
		echo "</br>我在Module层销毁前先执行";
	}

	public function update()
	{

	}

	public function login()
	{
		//新建两个观察者
		$ob1 = new identityObserver;
		$ob2 = new logObserver;

		//为当前方法添加观察者
		$this -> addObserver(__METHOD__, $ob1);
		$this -> addObserver(__METHOD__, $ob2);
		$this -> delObserver(__METHOD__, $ob2);

		//用于快速调用下一个层的方法，调用后会执行Dao层的同名方法
		N('d');

		//通知观察者，并且传递参数
		$this -> notifyObserver('login', 'UserModule\'s login function work!');
	}

}
?>