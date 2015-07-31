<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 28 Jul 2013
 * Project: maveric
 *
 */
namespace sys\libraries\database\sql;
require_once BASEPATH.'/libraries/database/core/RawObj.php';

/**
 * Class SqlDriver
 * @package sys\libraries\database\sql
  */
class MySqli
{

	/**
	 * @var \mysqli|null
	 */
	protected $_connection = null;

	/**
	 * @var \mysqli_result|null
	 */
	protected $_cursor = null;

	/**
	 * @var string
	 */
	protected $_last_query = '';

	public function __construct($options)
	{
		$this->connect($options);
	}

	protected function _ensureConnected($options)
	{
		if (!$this->is_connected())
		{
			$this->connect($options);
			if (!$this->is_connected())
			{
				throw new \Exception('Database not connected');
			}
		}
		return true;
	}

	public function disconnect()
	{
		$this->_connection->close();
	}

	public function connect($options)
	{
		if ( ! $options['port'])
		{
			$options['port'] = ini_get("mysqli.default_socket");
		}

		$this->_connection = new \mysqli($options['host'], $options['user'], $options['password'], $options['database'], (int)$options['port']);

		if ($this->_connection->connect_errno)
		{
			throw new \Exception('Failed to connect to MySQL: (' . $this->_connection->connect_errno . ') ' . $this->_connection->connect_error);
		}
	}

	public function is_connected()
	{
		return $this->_connection->ping();
	}

	/**
	 * @param $q
	 * @return MySqli
	 */
	public function query($q)
	{
		if (func_num_args() > 1)
		{
			$args = func_get_args();
			$q = $args[0];
			array_shift($args);
			$args = $this->_process_values($args);
			array_unshift($args, $q);
			$q = call_user_func_array('sprintf', $args);
		}

		$this->_last_query = $q;

		$this->_cursor = $this->_connection->query($q);

		if ($this->_cursor === false)
		{
			return null;
		}

		return $this;
	}

	public function fetch()
	{
		if ( ! $this->_cursor)
			return null;

		$res = array();
		if (method_exists('mysqli_result', 'fetch_all')) # Compatibility layer with PHP < 5.3
		{
			$res = $this->_cursor->fetch_all(MYSQL_ASSOC);
		}
		else
		{
			while ($tmp = $this->_cursor->fetch_array(MYSQL_ASSOC))
			{
				$res[] = $tmp;
			}
		}

		return $res;
	}

	public function record_count()
	{
		return $this->_cursor->num_rows;
	}

	public function affected_count()
	{
		return $this->_connection->affected_rows;
	}

	public function last_insert_id()
	{
		return $this->_connection->insert_id;
	}

	public function connection()
	{
		return $this->_connection;
	}

	public function cursor()
	{
		return $this->_cursor;
	}

	public function last_error()
	{
		return $this->_connection? $this->_connection->error: null;
	}

	public function last_errno()
	{
		return $this->_connection? $this->_connection->errno: 0;
	}

	public function last_query()
	{
		return $this->_last_query;
	}

	public function escape($str)
	{
		return $this->_connection->escape_string($str);
	}

	protected function _process_columns($columns)
	{
		$cols = array();
		foreach($columns as $c)
		{
			$cols[] = '`'.$c.'`';
		}
		return $cols;
	}

	protected function _process_values($values)
	{
		$data = array();
		foreach($values as $v)
		{
			if (is_string($v))
			{
				$v = $this->_connection->escape_string($v);
			}
			$data[] = $v;
		}
		return $data;
	}
}