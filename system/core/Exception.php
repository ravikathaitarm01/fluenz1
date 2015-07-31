<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 18 Jul 2013
 * Project: maveric
 *
 */

namespace sys\core;
/**
 * Class Exception
 *
 * Used to handle exceptions
 *
 * @package sys\core
 */
class Exception
{
	/**
	 * Display an error message then kill execution
	 * @param string $message
	 * @param int $code
	 * @param string $text
	 */
	public static function error($message, $code=200, $text='')
	{
		self::set_header($code, $text);
		die('Maveric Error: '.$message);
	}

	/**
	 * Display a message
	 * @param string $message
	 */
	public static function trace($message)
	{
		echo 'Maveric Trace: '.$message;
	}

	/**
	 * Set a HTTP response header
	 * @param int $code
	 * @param string $text
	 */
	public static function set_header($code=200, $text='')
	{
		$stati = array(
			200	=> 'OK',
			201	=> 'Created',
			202	=> 'Accepted',
			203	=> 'Non-Authoritative Information',
			204	=> 'No Content',
			205	=> 'Reset Content',
			206	=> 'Partial Content',

			300	=> 'Multiple Choices',
			301	=> 'Moved Permanently',
			302	=> 'Found',
			303	=> 'See Other',
			304	=> 'Not Modified',
			305	=> 'Use Proxy',
			307	=> 'Temporary Redirect',

			400	=> 'Bad Request',
			401	=> 'Unauthorized',
			403	=> 'Forbidden',
			404	=> 'Not Found',
			405	=> 'Method Not Allowed',
			406	=> 'Not Acceptable',
			407	=> 'Proxy Authentication Required',
			408	=> 'Request Timeout',
			409	=> 'Conflict',
			410	=> 'Gone',
			411	=> 'Length Required',
			412	=> 'Precondition Failed',
			413	=> 'Request Entity Too Large',
			414	=> 'Request-URI Too Long',
			415	=> 'Unsupported Media Type',
			416	=> 'Requested Range Not Satisfiable',
			417	=> 'Expectation Failed',
			422	=> 'Unprocessable Entity',

			500	=> 'Internal Server Error',
			501	=> 'Not Implemented',
			502	=> 'Bad Gateway',
			503	=> 'Service Unavailable',
			504	=> 'Gateway Timeout',
			505	=> 'HTTP Version Not Supported'
		);

		if (empty($code) OR ! is_numeric($code))
		{
			Exception::error('Status codes must be numeric', 500);
		}

		is_int($code) OR $code = (int) $code;

		if (empty($text))
		{
			if (isset($stati[$code]))
			{
				$text = $stati[$code];
			}
			else
			{
				Exception::error('No status text available. Please check your status code number or supply your own message text.', 500);
			}
		}

		$server_protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : false;

		if (strpos(php_sapi_name(), 'cgi') === 0)
		{
			header('Status: '.$code.' '.$text, true);
		}
		else
		{
			header(($server_protocol ? $server_protocol : 'HTTP/1.1').' '.$code.' '.$text, true, $code);
		}
	}
}