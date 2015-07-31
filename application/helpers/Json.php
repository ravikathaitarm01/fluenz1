<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 29 Jul 2013
 * Project: maveric
 *
 */
namespace app\helpers;

class Json
{
	public static function write($array)
	{
		die(json_encode($array));
	}

	public static function success($message, $redirect=null, $extra=array())
	{
		die(json_encode(array_merge(array(
			'success' => true,
			'message' => htmlspecialchars($message),
			'redirect' => $redirect,
		), $extra)));
	}

	public static function error($message, $redirect=null, $extra=array())
	{
		die(json_encode(array_merge(array(
			'success' => false,
			'error' => htmlspecialchars($message),
			'redirect' => $redirect,
		), $extra)));
	}
}