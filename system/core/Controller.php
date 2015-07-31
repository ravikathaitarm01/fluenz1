<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 17 Jul 2013
 * Project: maveric
 *
 */

namespace sys\core;

class Controller
{
	/**
	 * Singleton isntance
	 * @var null|Controller
	 */
	protected static $_instance = null;

	/**
	 * Returns the singleton instance for the controller
	 * @return Controller
	 */
	public static function instance()
	{
		if (self::$_instance === null)
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	protected  $_dispatch_request = true;
	protected $_params = array();

	public $input = null;
	public $config = null;
	public $db = null;
	public $log = null;

	public function __construct()
	{
		self::$_instance = $this;
		$this->input = new Input();
		$this->config = Config::instance();
		$this->db = Database::instance();
		$this->log = Log::instance();
		$this->log->write('debug', 'Controller Class Initialized');
	}

	public function dispatch_request()
	{
		return $this->_dispatch_request;
	}

	// --------------------------------------------------------------------

}