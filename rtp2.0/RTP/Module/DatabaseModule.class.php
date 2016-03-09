<?php
/**
 * 数据库模块，用于数据库创建以及一系列数据库操作
 * @author rolealiu/刘昊臻,www.rolealiu.com
 * @updateTime 20160308
 */

namespace RTP\Module;

class DatabaseModule
{
	use \RTP\Traits\Singleton;

	private static $db_con;
	private $db_history;
	//上一次操作结果
	private $last_result;
	private $last_sql;

	/**
	 * 创建对象时自动连接数据库
	 */
	protected function __construct()
	{
		self::connect();
	}

	/**
	 * 销毁对象时自动断开数据库连接
	 */
	function __destruct()
	{
		self::close();
	}

	/**
	 * 连接主机
	 */
	private function connect()
	{
		$conInfo = DB_TYPE . ':host=' . DB_URL . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8';

		//是否保持持久化链接
		if (DB_PERSISTENT_CONNECTION)
		{
			$option = array(
				\PDO::MYSQL_ATTR_INIT_COMMAND => "set names 'utf8'",
				\PDO::ATTR_PERSISTENT => TRUE,
				\PDO::ATTR_EMULATE_PREPARES => FALSE
			);
		}
		else
		{
			$option = array(
				\PDO::MYSQL_ATTR_INIT_COMMAND => "set names 'utf8'",
				\PDO::ATTR_EMULATE_PREPARES => FALSE
			);
		}

		//尝试连接数据库
		try
		{
			self::$db_con = new \PDO($conInfo, DB_USER, DB_PASSWORD, $option);

		}
		catch(\PDOException $e)
		{
			//输出错误信息
			print_r($e -> getMessage());
			exit ;
		}
	}

	/**
	 * 关闭主机连接
	 */
	public function close()
	{
		self::$db_con = null;
		self::$instance = null;
	}

	/**
	 * 执行无返回值的数据库操作
	 */
	public function execute($sql)
	{
		$this -> last_sql = $sql;
		$this -> db_history = self::$db_con -> exec($sql);
		$this -> getError();
		return $this -> db_history;
	}

	/**
	 * 执行操作并返回一条数据
	 */
	public function query($sql)
	{
		$this -> last_sql = $sql;
		$this -> db_history = self::$db_con -> query($sql);
		$this -> getError();
		$this -> last_result = $this -> db_history -> fetch(\PDO::FETCH_ASSOC);
		return $this -> last_result;
	}

	/**
	 * 执行操作并返回多条数据(如果可能)
	 */
	public function queryAll($sql)
	{
		$this -> last_sql = $sql;
		$this -> db_history = self::$db_con -> query($sql);
		$this -> getError();
		$this -> last_result = $this -> db_history -> fetchAll(\PDO::FETCH_ASSOC);
		return $this -> last_result;
	}

	/**
	 * prepare方式执行操作，返回一条数据，防止sql注入
	 */
	public function prepareExecute($sql, $params = null)
	{
		$this -> last_sql = $sql;
		$this -> db_history = self::$db_con -> prepare($sql);
		$this -> getError();
		if (is_null($params))
		{
			$this -> db_history -> execute();
		}
		else
		{
			$this -> db_history -> execute($params);
		}
		$this -> getError();
		$this -> last_result = $this -> db_history -> fetch(\PDO::FETCH_ASSOC);

		return $this -> last_result;
	}

	/**
	 * prepare方式执行操作，返回多条数据（如果可能），防止sql注入
	 */
	public function prepareExecuteAll($sql, $params = null)
	{
		$this -> last_sql = $sql;
		$this -> db_history = self::$db_con -> prepare($sql);
		$this -> getError();
		if (is_null($params))
		{
			$this -> db_history -> execute();
		}
		else
		{
			$this -> db_history -> execute($params);
		}
		$this -> getError();
		$this -> last_result = $this -> db_history -> fetchAll(\PDO::FETCH_ASSOC);

		return $this -> last_result;
	}

	/**
	 * prepare方式，以新的参数重新执行一次查询，返回一条数据
	 */
	public function prepareRexecute($params)
	{
		$this -> db_history -> execute($params);
		$this -> getError();
		$this -> last_result = $this -> db_history -> fetch(\PDO::FETCH_ASSOC);
		return $this -> last_result;
	}

	/**
	 * prepare方式，以新的参数重新执行一次查询，返回多条数据（如果可能）
	 */
	public function prepareRexecuteAll($params)
	{
		$this -> db_history -> execute($params);
		$this -> getError();
		$this -> last_result = $this -> db_history -> fetchAll(\PDO::FETCH_ASSOC);
		return $this -> last_result;
	}

	/**
	 * 获取上一次操作影响的行数
	 */
	public function getAffectRow()
	{
		if (is_null($this -> db_history))
		{
			return 0;
		}
		else
		{
			return $this -> db_history -> rowCount();
		}
	}

	/**
	 * 获取最后执行的SQL语句
	 */
	public function getLastSQL()
	{
		return $this -> last_sql;
	}

	/**
	 * 获取最后插入行的ID或序列值
	 */
	public function getLastInsertID()
	{
		return self::$db_con -> lastInsertId();
	}

	/**
	 * 获取错误信息
	 */
	public function getError()
	{
		if (DEBUG)
		{
			if (self::$db_con -> errorInfo()[0] != 00000)
				throw new ExceptionModule(12000, "database error in:{self::$db_con -> errorInfo()}");
		}
	}
	
	/**
	 * 开始事务
	 */
	 public function beginTransaction()
	 {
	 	self::$db_con->beginTransaction();
	 }
	 
	 /**
	  * 回滚事务
	  */
	 public function rollback()
	 {
	 	self::$db_con->rollback();
	 }
	 
	 /**
	  * 提交事务
	  */
	 public function commit()
	 {
	 	self::$db_con->commit();
	 }

}
?>
