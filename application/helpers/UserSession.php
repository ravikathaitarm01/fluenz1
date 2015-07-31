<?php
/**
 * User: Nisheeth
 * Date: 7/19/13
 * Time: 2:00 PM
 * To change this template use File | Settings | File Templates.
 */

namespace app\helpers;
use sys\core\Controller;

class UserSession
{
	public static function get($keys)
	{
		$keys = $keys ? explode('.', $keys) : array();
		array_unshift($keys, 'user_session');
		return call_user_func_array(array(Controller::instance()->input, 'session'), $keys);
	}

	public static function set($keys, $value)
	{
		$keys = $keys ? explode('.', $keys) : array();
		array_unshift($keys, 'user_session');
		array_push($keys, $value);
		call_user_func_array(array(Controller::instance()->input, 'set_session'), $keys);
	}

	public static function erase($keys)
	{
		$keys = $keys ? explode('.', $keys) : array();
		array_unshift($keys, 'user_session');
		call_user_func_array(array(Controller::instance()->input, 'unset_session'), $keys);
	}

	public static function destroy()
	{
		Controller::instance()->input->unset_session('user_session');
	}
}