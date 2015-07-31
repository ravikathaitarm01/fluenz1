<?php
/**
 * User: Nisheeth
 * Date: 7/19/13
 * Time: 2:00 PM
 * To change this template use File | Settings | File Templates.
 */

namespace app\helpers;
use sys\core\Controller;
use sys\helpers\Url as BaseUrl;

class Url extends BaseUrl
{
	public static function asset($uri='')
	{
		return Controller::instance()->config->site_url('asset', $uri);
	}

	public static function asset_path($uri='')
	{
		return Controller::instance()->config->site_url('asset_path', $uri);
	}

	public static function view($path)
	{
		return VIEWPATH.ltrim($path, '/');
	}

	public static function referrer()
	{
		return $_SERVER['HTTP_REFERER'];
	}
}