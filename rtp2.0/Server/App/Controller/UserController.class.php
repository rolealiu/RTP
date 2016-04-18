<?php
class UserController
{

	public function login()
	{

		//前端传来的参数用quickInput()获取,不管是GET还是POST的，框架会自动过滤数据
		//$param = quickInput('userName');
		//因为暂时没有前台和数据库，这里模拟一下就好了
		$param = 'hello world';
		$server = new UserModule;
		echo $server -> login($param);
	}

}
?>