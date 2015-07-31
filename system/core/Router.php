<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 17 Jul 2013
 * Project: maveric
 *
 */

namespace sys\core;

class Router
{
	protected static function _route_method($rel_uri)
	{
		// Convert the path to lowercase for namespaces
		$path = explode('/', $rel_uri);

		// Split controllers and parameters
		$url_parts = array(
			'controller' => $rel_uri,
			'params' => null,
		) ;

		$c_path = APPPATH.'controllers'.DIRECTORY_SEPARATOR;
		foreach ($path as $p)
		{
			if (is_dir($c_path.$p))
			{
				$c_path .= strtolower($p).DIRECTORY_SEPARATOR;
			}
			else if (is_file($c_path.ucwords($p).'.php'))
			{
				$c_path .= strtolower($p).DIRECTORY_SEPARATOR;
				$url_parts['controller'] = rtrim(substr($c_path, strlen(APPPATH.'controllers'.DIRECTORY_SEPARATOR)), '/');
				break;
			}
		}

		if ($t = str_replace($url_parts['controller'], '', $rel_uri))
		{
			$url_parts['params'] = explode('/', trim($t, '/'));
		}
		else
		{
			$url_parts['params'] = null;
		}

		// Capitalize the last item (class name) and strip the extension
		$c_namespace = explode('/', $url_parts['controller']);
		$ridx = count($c_namespace) - 1;
		$c_namespace[$ridx] = str_replace('-', '_', trim(ucwords($c_namespace[$ridx])));

		if ( ! $c_namespace[$ridx] )
		{
			die('Bad controller: '.$c_namespace[$ridx]);
		}

		// Set PHP_SELF
		$_SERVER['PHP_SELF'] = implode('/', $c_namespace).'.php';

		// Prepend the necessary namespace part
		$class = 'app\\controllers\\'.implode('\\', $c_namespace);

		// Include the class
		if ( ! class_exists($class))
		{
			Exception::error('Invalid controller');
		}

		// Create instance
		/**
		 * @var $MVC \sys\core\Controller
		 */
		$MVC = new $class();

		// Assign a valid method depending upon the REQUEST_METHOD
		// If no handler is specified, then index is called
		$method = 'index';
		$params = array();
		if ($url_parts['params'] !== null)
		{
			$method = array_shift($url_parts['params']);
			$params = $url_parts['params'];
		}

		if ( ! is_callable(array($class, $method)))
		{
			Exception::error('No method defined to handle request');
		}

		// And we have lift off...
		if ($MVC->dispatch_request())
		{
			call_user_func_array(array($MVC, $method), $params);
		}
	}

	protected static function _route_class($rel_uri)
	{
		// Convert the path to lowercase for namespaces
		$path = explode('/', rtrim($rel_uri, '/'));

		// Split controllers and parameters
		$url_parts = array(
			'controller' => $rel_uri,
			'params' => null,
		) ;

		$c_path = APPPATH.'controllers'.DIRECTORY_SEPARATOR;
		$found = null;

		foreach ($path as $p)
		{
			if (is_dir($c_path.$p))
			{
				$c_path .= strtolower($p).DIRECTORY_SEPARATOR;
				// Test Index.php
				if (is_file($c_path.'Index.php'))
				{
					$found = array(
						'path' => $c_path.'index'.DIRECTORY_SEPARATOR,
						'controller' => rtrim(substr($c_path.'index'.DIRECTORY_SEPARATOR, strlen(APPPATH.'controllers'.DIRECTORY_SEPARATOR)), '/')
					);
					if ( ($t = rtrim(substr($rel_uri, strlen($found['controller']) - strlen('/index')), '/')) !== false)
					{
						$url_parts['params'] = $t? explode('/', trim($t, '/')): null;
					}
				}
			}
			else if (is_file($c_path.ucwords($p).'.php'))
			{
				$c_path .= strtolower($p).DIRECTORY_SEPARATOR;
				$url_parts['controller'] = rtrim(substr($c_path, strlen(APPPATH.'controllers'.DIRECTORY_SEPARATOR)), '/');
				$found = array(
					'path' => $c_path,
					'controller' => $url_parts['controller']
				);
				if ($t = str_replace($url_parts['controller'], '', $rel_uri))
				{
					$url_parts['params'] = explode('/', trim($t, '/'));
				}
				else
				{
					$url_parts['params'] = null;
				}
			}
			else
			{
				if ($found)
				{
					break;
				}
			}
		}

		$url_parts['controller'] = $found['controller'];

		// Capitalize the last item (class name) and strip the extension
		$c_namespace = explode('/', $url_parts['controller']);
		$ridx = count($c_namespace) - 1;
		$c_namespace[$ridx] = str_replace('-', '_', trim(ucwords($c_namespace[$ridx])));

		if ( ! $c_namespace[$ridx] )
		{
			_mvc_dispatch_error('Controller Not Found');
		}

		// Set PHP_SELF
		$_SERVER['PHP_SELF'] = implode('/', $c_namespace).'.php';

		// Prepend the necessary namespace part
		$class = 'app\\controllers\\'.implode('\\', $c_namespace);

		// Include the class
		if ( ! class_exists($class))
		{
			_mvc_dispatch_error('Invalid Controller: '.$class);
		}

		// Create instance
		/**
		 * @var $MVC \sys\core\Controller
		 */
		$MVC = new $class();

		// Assign a valid method depending upon the REQUEST_METHOD
		// If no handler is specified, then index is called
		$method = 'index';
		$params = $url_parts['params'];
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_callable(array($class, 'post')))
		{
			$method = 'post';
		}
		elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && is_callable(array($class, 'get')))
		{
			$method = 'get';
		}

		if ( ! is_callable(array($class, $method)))
		{
			_mvc_dispatch_error('No method defined to handle request for: '.$class.' '.$method);
		}

		// And we have lift off...
		if ($MVC->dispatch_request())
		{
			call_user_func_array(array($MVC, $method), $params?:array());
		}
	}

	public static function route($rel_uri)
	{
		try {
			if (ROUTER === 'route_method')
			{
				self::_route_method($rel_uri);
			}
			else
			{
				self::_route_class($rel_uri);
			}
		} catch (\Exception $e) {
			_mvc_dispatch_exception($e);
		}

	}
}
