<?php

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
|--------------------------------------------------------------------------
| Custom Constants
|--------------------------------------------------------------------------
|
| Custom Constants go here
|
*/
//define('ROUTER', 'route_method');
define('ROUTER', 'route_class');
define ('ROOT', $_SERVER['DOCUMENT_ROOT'].'/');
define ('TIMEZONE', 'Asia/Kolkata');


// Error Handlers
function _mvc_dispatch_error($e)
{
	if ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
	{
		die(json_encode(array('success' => false, 'error' => '500 Error '.$e)));
	}
	if (php_sapi_name() === 'cli')
	{
		die($e);
	}
	$d = new \sys\libraries\Display();
	$d->attach('Url', new \app\helpers\Url(), true);
	$d->view('404.php', array('error' => $e), false, false);
	die();
}

/**
 * @param \Exception $e
 */
function _mvc_dispatch_exception($e)
{
	if ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
	{
		//header('HTTP/1.1 500 '.$e->getMessage());
		die(json_encode(array('success' => false, 'error' => '500 Error '.$e->getMessage())));
	}
	if (php_sapi_name() === 'cli')
	{
		die($e->getTraceAsString());
	}
	if ($t = $e->getFile())
	{
		$t .= ':'.$e->getLine().' > ';
	}
	else
	{
		$t = '';
	}
	$d = new \sys\libraries\Display();
	$d->attach('Url', new \app\helpers\Url(), true);
	$d->view('500.php', array('error' => $t.$e->getMessage()), false, false);
	die();
}