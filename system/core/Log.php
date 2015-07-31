<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 18 Jul 2013
 * Project: maveric
 *
 */

namespace sys\core;

class Log
{
	/**
	 * Singleton instance
	 * @var null
	 */
	protected static $_instance = null;

	/**
	 * Returns the singleton instance for the class
	 * @return Log
	 */
	public static function instance()
	{
		if (self::$_instance === null)
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	protected $_enabled = true;

	protected $_log_path;

	protected $_threshold		= 1;

	protected $_threshold_max	= 0;

	protected $_threshold_array	= array();

	protected $_date_fmt		= 'Y-m-d H:i:s';

	protected $_file_ext;

	protected $_levels		= array('ERROR' => 1, 'DEBUG' => 2, 'INFO' => 3, 'ALL' => 4);

	// --------------------------------------------------------------------

	public function __construct()
	{
		$config = Config::instance();

		// Check if logging is enabled. Default: enabled
		$this->_enabled = $config->item('log', 'enabled') !== null? (bool)$config->item('log', 'enabled'): true;

		if ($this->_enabled)
		{
			// Assign a default log path if not specified in config
			$this->_log_path = ($config->item('log', 'path') !== '') ? $config->item('log', 'path') : APPPATH.'logs/';

			// Assign a default log extension if not specified in config
			$this->_file_ext = ($config->item('log', 'file_extension') && $config->item('log', 'file_extension') !== '')
				? ltrim($config->item('log', 'file_extension'), '.') : 'php';

			// Create the log directory if it doesn't exist
			file_exists($this->_log_path) OR mkdir($this->_log_path, DIR_WRITE_MODE, true);

			// Wait what? We failed to create the directory. Abort!
			if ( ! is_dir($this->_log_path))
			{
				Exception::trace('Could not create the logging directory');
			}

			if (is_numeric($config->item('log', 'threshold')))
			{
				$this->_threshold = (int) $config->item('log', 'threshold');
			}
			elseif (is_array($config->item('log', 'threshold')))
			{
				$this->_threshold = $this->_threshold_max;
				$this->_threshold_array = array_flip($config->item('log', 'threshold'));
			}

			if ($config->item('log', 'date_format') !== '')
			{
				$this->_date_fmt = $config->item('log', 'date_format');
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Write the message to the log file
	 * @param string $level
	 * @param string $msg
	 * @return bool
	 */
	public function write($level = 'error', $msg)
	{
		if ($this->_enabled === false)
		{
			return false;
		}

		$level = strtoupper($level);

		if (( ! isset($this->_levels[$level]) OR ($this->_levels[$level] > $this->_threshold))
			&& ! isset($this->_threshold_array[$this->_levels[$level]]))
		{
			return false;
		}

		$filepath = $this->_log_path.'log-'.date('Y-m-d').'.'.$this->_file_ext;
		$message = '';

		if ( ! file_exists($filepath))
		{
			$newfile = true;
		}

		if ( ! $fp = @fopen($filepath, FOPEN_WRITE_CREATE))
		{
			return false;
		}

		$message .= $level.' '.($level === 'INFO' ? ' -' : '-').' '.date($this->_date_fmt).' --> '.$msg."\n";

		flock($fp, LOCK_EX);
		fwrite($fp, $message);
		flock($fp, LOCK_UN);
		fclose($fp);

		if (isset($newfile) && $newfile === true)
		{
			@chmod($filepath, FILE_WRITE_MODE);
		}

		return true;
	}
}