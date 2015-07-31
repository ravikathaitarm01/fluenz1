<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 17 Jul 2013
 * Project: maveric
 *
 */

namespace sys\core;

// Load all the necesary files

require APPPATH.'config/constants.php';
require BASEPATH.'core/Exception.php';
require BASEPATH.'core/Loader.php';
require BASEPATH.'core/Router.php';

/**
 * Custom Includes
 */


// Maveric version
define('MVC_VERSION', '1.1');

$rel_uri = '';
if (php_sapi_name() === 'cli')
{
	global $argv;
	$uri = '/'.ltrim($argv[1], '/');
	$_SERVER['REQUEST_URI'] = $uri;
	if (strncmp($uri, '/', 1) === 0)
	{
		$uri = explode('?', $uri, 2);
		$_SERVER['QUERY_STRING'] = isset($uri[1]) ? $uri[1] : '';
		$uri = rawurldecode($uri[0]);
	}
	else
	{
		die('Awkward path mate.');
	}

	$rel_uri = ltrim($uri, '/');

	// Re-assign $_GET array
	parse_str($_SERVER['QUERY_STRING'], $_GET);
	$_SERVER['REQUEST_METHOD'] = 'GET';
}
else
{
	// Create path from URI
	$uri = $_SERVER['REQUEST_URI'];
	if (strncmp($uri, '/', 1) === 0)
	{
		$uri = explode('?', $uri, 2);
		$_SERVER['QUERY_STRING'] = isset($uri[1]) ? $uri[1] : '';
		$uri = rawurldecode($uri[0]);
	}
	else
	{
		die('Awkward path mate.');
	}

	// Re-assign $_GET array
	parse_str($_SERVER['QUERY_STRING'], $_GET);

	// Get the requested controller. Assign the default controller
	// from the config file if no controller is requested.
	$rel_uri = ltrim(substr($uri, strlen($_SERVER['SCRIPT_NAME']) - strlen('/index.php')), '/');
}

if (empty($rel_uri))
{
	$rel_uri = trim(Config::instance()->item('site', 'default_controller'));
	if( ! $rel_uri)
		die('No controller specified');
}

Router::route($rel_uri);
