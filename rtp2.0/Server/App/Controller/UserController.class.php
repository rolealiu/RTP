<?php
//use RTP\Module as M;
class UserController
{

	public function login()
	{
		$server = new UserModule;
		$server->login();
	}

}
?>