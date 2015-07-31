<?php
/**
 * User: Nisheeth
 * Date: 7/19/13
 * Time: 2:00 PM
 * To change this template use File | Settings | File Templates.
 */

namespace app\helpers;
use app\libraries\facebook\SDK as FacebookSDK;
use app\libraries\facebook\FacebookAccessTokenException;

class MongoDoc
{
	protected static function _get($array, $index=array())
	{
		$value = null;
		foreach($index as $arg)
		{
			if (isset($array[$arg]))
			{
				$value = $array = $array[$arg];
			}
			else
			{
				return null;
			}
		}

		return $value;
	}

	protected static function _set(&$array, $index=array(), $o)
	{
		$value = null;
		foreach($index as $arg)
		{
			if ( ! isset($array[$arg]) || ! is_array($array[$arg]))
			{
				$array[$arg] = array();
			}
			$array = &$array[$arg];
		}

		$array = $o;
		return $o;
	}

	protected static function _keys($key)
	{
		$a = array();
		$append = false;
		$i = 0;
		foreach (explode('.', $key) as $k)
		{
			if ($append)
			{
				$a[$i-1] .= '.'.trim($k, '\\');
			}
			else
			{
				$a[] = trim($k, '\\');
				$i++;
			}
			$append = substr($k, -1, 1) == '\\';
		}
		return $a;
	}

	public static function get($array, $key, $default=null)
	{
		if ($key)
		{
			$v = self::_get($array, self::_keys($key));
			return $v !== null? $v: $default;
		}
		return $array;
	}

	public static function set(&$array, $key, $value)
	{
		return $key
				? (self::_set($array, self::_keys($key), $value))
				: ($array = $value);
	}

	protected static function _explode_recursion($array, &$out)
	{
		if (is_array($array))
		{
			foreach (array_keys($array) as $k)
			{
				$restore = false;
				$parts = explode('.', $k);
				if (count($parts))
				{
					$result = array();
					$r = &$result;
					foreach ($parts as $t)
					{
						$r[$t] = array();
						$r = &$r[$t];
					}
					$out = array_merge_recursive($out, $result);
					$orig_out = &$out;
					$restore = true;
					foreach ($parts as $t)
					{
						$out = &$out[$t];
					}
				}
				else
				{
					$out[$k] = $array[$k];
					$out = &$out[$k];
				}
				self::_explode_recursion($array[$k], $out);
				if ($restore)
				{
					$out = &$orig_out;
				}
			}
		}
		else
		{
			$out = $array;
		}
	}

	public static function explode($array)
	{
		$out = array();
		self::_explode_recursion($array, $out);
		return $out;
	}
}
