<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 17 Jul 2013
 * Project: maveric
 *
 */

namespace sys\core;

/**
 * Class Input
 *
 * Manages the PHP global input variables
 *
 * @package sys\core
 */
class Input
{
	/**
	 * HTTP headers
	 * @var array
	 */
	protected $_headers = array();

	public function __construct()
	{
		// Start session if not already started
		if (!session_id())
		{
			session_start();
		}

		$this->config = Config::instance();
		$this->log = Log::instance();
		$this->log->write('debug', 'Input Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Walks an array to return the value
	 * @param array $array The source array
	 * @param array $index array of nested indexes
	 * @return null|mixed
	 */
	protected function _fetch_from_array($array, $index = array())
	{
		$value = null;

		foreach($index as $arg)
		{
			if (isset($array[$arg]))
			{
				$value = $array = $array[$arg];
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
	 * Returns the complete array if the index is empty, else attempts to
	 * return the value if exists
	 * @param array $array The source array
	 * @param array $index array of nested indexes
	 * @return null|mixed
	 */
	protected function _fetch_with_default($array, $index)
	{
		// Check if a field has been provided, if not then
		// return the complete array
		if ( ! count($index) || empty($index[0]) === null)
		{
			if (empty($array))
			{
				return array();
			}

			return $array;
		}

		return $this->_fetch_from_array($array, $index);
	}

	// --------------------------------------------------------------------

	/**
	 * Returns an item from the $_GET array
	 * @param null $index Variadic list of indexes
	 * @return mixed|null
	 */
	public function get($index = null)
	{
		$index = func_get_args();
		return $this->_fetch_with_default($_GET, $index);
	}

	// --------------------------------------------------------------------

	/**
	 * Returns an item from the $_POST array
	 * @param null $index Variadic list of indexes
	 * @return mixed|null
	 */
	public function post($index = null)
	{
		$index = func_get_args();
		return $this->_fetch_with_default($_POST, $index);
	}

	// --------------------------------------------------------------------

	/**
	 * Returns an item from the $_REQUEST array
	 * @param null $index Variadic list of indexes
	 * @return mixed|null
	 */
	public function request($index = null)
	{
		$index = func_get_args();
		return $this->_fetch_with_default($_REQUEST, $index);
	}

	// --------------------------------------------------------------------

	/**
	 * Returns an item from the $_COOKIE array
	 * @param null $index Variadic list of indexes
	 * @return mixed|null
	 */
	public function cookie($index = null)
	{
		// Check if a field has been provided
		$index = func_get_args();
		return $this->_fetch_with_default($_COOKIE, $index);
	}

	// --------------------------------------------------------------------

	/**
	 * Returns an item from the $_SESSION array
	 * @param null $index Variadic list of indexes
	 * @return mixed|null
	 */
	public function session($index = null)
	{
		$index = func_get_args();
		return $this->_fetch_with_default($_SESSION, $index);
	}

	// --------------------------------------------------------------------

	/**
	 * Returns an item from the $_SERVER array
	 * @param null $index Variadic list of indexes
	 * @return mixed|null
	 */
	public function server($index = null)
	{
		$index = func_get_args();
		return $this->_fetch_with_default($_SERVER, $index);
	}

	// ------------------------------------------------------------------------

	/**
	 * Sets a cookie
	 * @param string $name
	 * @param string $value
	 * @param string $expire
	 * @param string $domain
	 * @param string $path
	 * @param string $prefix
	 * @param bool $secure
	 * @param bool $httponly
	 */
	public function set_cookie($name = '', $value = '', $expire = '', $domain = '', $path = '/', $prefix = '', $secure = false, $httponly = false)
	{
		if (is_array($name))
		{
			// always leave 'name' in last place, as the loop will break otherwise, due to $$item
			foreach (array('value', 'expire', 'domain', 'path', 'prefix', 'secure', 'httponly', 'name') as $item)
			{
				if (isset($name[$item]))
				{
					$$item = $name[$item];
				}
			}
		}

		if ($prefix === '' && $this->config->item('cookie', 'prefix') !== '')
		{
			$prefix = $this->config->item('cookie', 'prefix');
		}

		if ($domain == '' && $this->config->item('cookie', 'domain') != '')
		{
			$domain = $this->config->item('cookie' ,'domain');
		}

		if ($path === '/' && $this->config->item('cookie', 'path') !== '/')
		{
			$path = $this->config->item('cookie', 'path');
		}

		if ($secure === false && $this->config->item('cookie', 'secure') !== false)
		{
			$secure = $this->config->item('cookie', 'secure');
		}

		if ($httponly === false && $this->config->item('cookie', 'httponly') !== false)
		{
			$httponly = $this->config->item('cookie', 'httponly');
		}

		if ( ! is_numeric($expire))
		{
			$expire = time() - 86500;
		}
		else
		{
			$expire = ($expire > 0) ? time() + $expire : 0;
		}

		setcookie($prefix.$name, $value, $expire, $path, $domain, $secure, $httponly);
	}

	// --------------------------------------------------------------------

	/**
	 * Sets a session var
	 * @param $index
	 * @param $value
	 */
	public function set_session($index, $value)
	{
		$index = func_get_args();
		$value = array_pop($index);

		$array = &$_SESSION;
		foreach ($index as $idx)
		{
			if ( ! isset($array[$idx]))
			{
				$array[$idx] = array();
			}
			$array = &$array[$idx];
		}
		$array = $value;
	}

	// --------------------------------------------------------------------

	/**
	 * Unsets a session var
	 * @param $index
	 */
	public function unset_session($index)
	{
		$index = func_get_args();
		$array = &$_SESSION;
		$top = null;
		$idx = null;
		foreach ($index as $idx)
		{
			if ( ! isset($array[$idx]))
			{
				$array[$idx] = array();
			}
			$top = &$array;
			$array = &$array[$idx];
		}

		if (is_array($top))
		{
			unset($top[$idx]);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Destroys the current session
	 */
	public function destroy_session()
	{
		session_destroy();
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the HTTP headers for the current request
	 * @return array
	 */
	public function headers()
	{
		// In Apache, you can simply call apache_request_headers()
		if (function_exists('apache_request_headers'))
		{
			$headers = apache_request_headers();
		}
		else
		{
			$headers['Content-Type'] = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : @getenv('CONTENT_TYPE');

			foreach ($_SERVER as $key => $val)
			{
				if (sscanf($key, 'HTTP_%s', $header) === 1)
				{
					$headers[$header] = $this->_fetch_from_array($_SERVER, $key);
				}
			}
		}

		// take SOME_HEADER and turn it into Some-Header
		foreach ($headers as $key => $val)
		{
			$key = str_replace(array('_', '-'), ' ', strtolower($key));
			$key = str_replace(' ', '-', ucwords($key));

			$this->_headers[$key] = $val;
		}

		return $this->_headers;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns an item from the current HTTP headers
	 * @param $index
	 * @return null
	 */
	public function header_item($index)
	{
		if (empty($this->_headers))
		{
			$this->headers();
		}

		if ( ! isset($this->_headers[$index]))
		{
			return null;
		}

		return $this->_headers[$index];
	}

	// --------------------------------------------------------------------

	/**
	 * Returns true if the request is caused by AJAX
	 * @return bool
	 */
	public static function is_ajax_request()
	{
		return ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
	}

	// --------------------------------------------------------------------

	/**
	 * Returns true if the PHP request is from CLI
	 * @return bool
	 */
	public static function is_cli_request()
	{
		return (php_sapi_name() === 'cli' OR defined('STDIN'));
	}

	// --------------------------------------------------------------------

	/**
	 * Return true of HTTPS is enabled
	 * @return bool
	 */
	public static function is_https()
	{
		return (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) === 'on');
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the method for the current request
	 * @param bool $upper If set to true, the value is returned in uppercase
	 * @return string
	 */
	public static function method($upper = true)
	{
		return ($upper)
			? strtoupper($_SERVER['REQUEST_METHOD'])
			: strtolower($_SERVER['REQUEST_METHOD']);
	}
}
