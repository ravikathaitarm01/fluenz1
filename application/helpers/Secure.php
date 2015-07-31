<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 29 Jul 2013
 * Project: maveric
 *
 */
namespace app\helpers;

class Secure
{
	public static function password($pwd, $salt)
	{
		return sha1(md5($pwd.strrev($salt)).$salt);
	}

	public static function token_encode($items, $timestamp=true)
	{
		$token = '';
		foreach ($items as $item)
		{
			$token = sha1($token . md5($item)) . md5(strrev($item));
		}

		if ($timestamp)
		{
			$time = dechex(time());
			$part = str_split($time, 4);
			$token = $part[0].$token.$part[1];
		}
		return $token;
	}

	public static  function token_decode($token, $timestamp=true)
	{
		$time = null;
		if ($timestamp)
		{
			$time = hexdec(substr($token, 0, 4).substr($token, -4, 4));
			$token = substr($token, 4, strlen($token)-8);
		}

		return array(
			'token' => $token,
			'time'  => $time
		);
	}
}