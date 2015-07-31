<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 17 Jul 2013
 * Project: maveric
 *
 */

namespace sys\core;

/**
 * Class Config
 *
 * Parses and stores the config of the framework
 *
 * @package sys\core
 */
class Config
{
	/**
	 * @var Config $instance Singleton instance of the class
	 */
	protected static $_instance = null;

	/**
	 * @return Config Return the singleton instance of the class
	 */
	public static function instance()
	{
		if (self::$_instance === null)
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public $config = array();

	/**
	 * @var array List of config files already loaded
	 */
	protected $_loaded_files = array();

	/**
	 * @var array Directories to look for config files
	 */
	public $_config_paths =	array(APPPATH);


	public function __construct()
	{
		// Load the config files
		$this->load();

		// Set the base_url automatically if none was provided
		if (empty($this->config['base_url']))
		{
			if (isset($_SERVER['HTTP_HOST']))
			{
				$base_url = Input::is_https() ? 'https' : 'http';
				$base_url .= '://'.$_SERVER['HTTP_HOST']
					.str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
			}
			else
			{
				$base_url = 'http://localhost/';
			}

			$this->set_item(array('site', 'base_url'), $base_url);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Loads a config file and appends to the existing config
	 * @param string $file File to load config from.
	 * @return bool
	 */
	public function load($file = '')
	{
		$file = ($file === '') ? 'config' : str_replace('.php', '', $file);
		$found = $loaded = false;
		$file_path = null;

		foreach ($this->_config_paths as $path)
		{
			foreach (array(ENVIRONMENT.'/'.$file, $file) as $location)
			{
				$file_path = $path.'config/'.$location.'.php';

				// Check if we have already loaded the file
				if (in_array($file_path, $this->_loaded_files, true))
				{
					$loaded = true;
					continue 2;
				}

				if (file_exists($file_path))
				{
					$found = true;
					break;
				}
			}

			if ($found === false)
			{
				continue;
			}

			require_once($file_path);

			// Whoa! We didn't find no $config var... Abort!
			if ( ! isset($config) OR ! is_array($config))
			{
				Exception::error('Your '.$file_path.' file does not appear to contain a valid configuration array.');
			}

			/** @var $config array */
			$this->config = array_merge($this->config, $config);

			$this->_loaded_files[] = $file_path;

			// Clean up $config var for the next customer
			unset($config);

			$loaded = true;
			break;
		}

		if ($loaded === false)
		{
			Exception::error('The configuration file '.$file.'.php does not exist.');
		}

		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns all a config item
	 * @param string $key1 Variadic list of keys leading up to value
	 * @param string $key2
	 * @return null|mixed
	 */
	public function item($key1, $key2='')
	{
		$args = func_get_args();
		$container = $this->config;
		$value = null;
		foreach($args as $arg)
		{
			if (isset($container[$arg]))
			{
				$value = $container = $container[$arg];
			}
			else
			{
				return null;
			}
		}
		return $value;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns all config items
	 * @return array
	 */
	public function items()
	{
		return $this->config;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the config item with a trailing slash
	 * @param string $key1 Variadic list of keys leading up to value
	 * @param string $key2
	 * @return string
	 */
	public function slash_item($key1, $key2='')
	{
		$args = func_get_args();
		return rtrim(call_user_func_array(array($this, 'item'), $args), '/').'/';
	}

	// -------------------------------------------------------------

	/**
	 * Returns the base site url for the given URI part
	 * @param string $uri
	 * @return string
	 */
	public function base_url($uri = '')
	{
		return $this->slash_item('site', 'base_url').ltrim($this->_uri_string($uri), '/');
	}

	// -------------------------------------------------------------

	/**
	 * Return a site's ${type}_url from the config file
	 * @param string $type Type of url
	 * @param string $uri
	 * @return string
	 */
	public function site_url($type, $uri = '')
	{
		return $this->slash_item('site', $type.'_url').ltrim($this->_uri_string($uri), '/');
	}

	// -------------------------------------------------------------

	/**
	 * Creates a URI string from
	 * @param $uri
	 * @return string
	 */
	protected function _uri_string($uri)
	{
		if ($this->item('enable_query_strings') === false)
		{
			if (is_array($uri))
			{
				$uri = implode('/', $uri);
			}
			return trim($uri, '/');
		}
		elseif (is_array($uri))
		{
			return http_build_query($uri);
		}

		return $uri;
	}

	// --------------------------------------------------------------------

	public function system_url()
	{
		$x = explode('/', preg_replace('|/*(.+?)/*$|', '\\1', BASEPATH));
		return $this->slash_item('site', 'base_url').end($x).'/';
	}

	// --------------------------------------------------------------------

	public function set_item($key, $value)
	{
		if ( ! is_array($key))
		{
			$key = array($key);
		}

		$container = &$this->config;
		$last = null;
		$break_on = end($key);

		foreach($key as $arg)
		{
			if ( ! isset($container[$arg]))
			{
				$container[$arg] = null;
			}

			$last = &$container;

			if ($arg === $break_on)
			{
				break;
			}

			$container = &$container[$arg];
		}
		$last[$break_on] = $value;
	}

}