<?php
/**
 * User: Nisheeth
 * Date: 7/19/13
 * Time: 2:00 PM
 * To change this template use File | Settings | File Templates.
 */

namespace app\helpers;

class Output
{
	public static function time($ts)
	{
		return date('l jS F Y, H:i', is_string($ts)? strtotime($ts): $ts);
	}

	public static function url($link, $name=null)
	{
		return sprintf('<a href="%s" target="_blank">%s</a>', $link, $name?:$link);
	}

	public static function number($n)
	{
		return $n > 9999
			? round($n/1000, 2).'k'
			: $n;
	}
}