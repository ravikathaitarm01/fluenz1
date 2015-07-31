<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 19 Jul 2013
 * Project: maveric
 *
 */

namespace sys\core;

/**
 * Class Library
 * @package sys\core
 */
class Library
{
	protected $input = null;
	protected $config = null;
	protected $log = null;

	public function __construct()
	{
		$this->input = new Input();
		$this->config = Config::instance();
		$this->log = Log::instance();
		$this->log->write('debug', 'Library Class Initialized');
	}
}