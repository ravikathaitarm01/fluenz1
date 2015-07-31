<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 18 Jul 2013
 * Project: maveric
 *
 */

namespace sys\helpers;
use sys\core\Controller;

/**
 * Helper for URL generation and redirections
 * Class Url
 * @package sys\helpers
 */
class Url
{
	/**
	 * Generates a URL for the given URI and type
	 * @param string $type Type defined in the 'site' section of config
	 * @param string $uri
	 * @return string
	 */
	public static function site($type, $uri='')
	{
		return Controller::instance()->config->site_url($type, $uri);
	}

	/**
	 * Generates a base URL for the given URI
	 * @param string $uri
	 * @return string
	 */
	public static function base($uri='')
	{
		return Controller::instance()->config->base_url($uri);
	}

	/**
	 * Returns the current URL
	 * @return mixed
	 */
	public static function current($query_string=true)
	{
		return $query_string
				? substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['SCRIPT_NAME'])))
				: $_SERVER['PHP_SELF'];
	}

	/**
	 * Performs a redirection to the given URI
	 * @param string $uri
	 * @param string $method
	 * @param null $code
	 */
	public static function redirect($uri = '', $method = 'auto', $code = NULL)
	{
		if ( ! preg_match('#^(\w+:)?//#i', $uri))
		{
			$uri = self::base($uri);
		}

		// IIS environment likely? Use 'refresh' for better compatibility
		if ($method === 'auto' && isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== FALSE)
		{
			$method = 'refresh';
		}
		elseif ($method !== 'refresh' && (empty($code) OR ! is_numeric($code)))
		{
			// Reference: http://en.wikipedia.org/wiki/Post/Redirect/Get
			$code = (isset($_SERVER['REQUEST_METHOD'], $_SERVER['SERVER_PROTOCOL'])
				&& $_SERVER['REQUEST_METHOD'] === 'POST'
				&& $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.1')
				? 303 : 302;
		}

		switch ($method)
		{
			case 'refresh':
				header('Refresh:0;url='.$uri);
				break;
			default:
				header('Location: '.$uri, true, $code);
				break;
		}
		exit;
	}

	/**
	 * Gets the clean domain name from a url
	 * @param $url
	 * @return string|null
	 */
	public static function get_domain($url)
	{
		if (preg_match('!(?:https?://)?(?:www\.)?([^/]+).*$!', $url, $m))
		{
			return $m[1];
		}

		return null;
	}
}