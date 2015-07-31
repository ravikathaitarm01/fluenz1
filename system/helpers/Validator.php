<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 29 Jul 2013
 * Project: maveric
 *
 */
namespace sys\helpers;

class Validator
{
	public static function is_url($str)
	{
		if (empty($str))
		{
			return false;
		}
		elseif (preg_match('/^https?/', $str))
		{
			return (bool) filter_var($str, FILTER_VALIDATE_URL);
		}

		return false;
	}

	public static function is_email($str)
	{
		if (empty($str))
		{
			return false;
		}
		return (bool) filter_var($str, FILTER_VALIDATE_EMAIL);
	}

	public static function is_base64($str)
	{
		if (empty($str))
		{
			return false;
		}
		return ! preg_match('/[^a-zA-Z0-9\/\+=]/', $str);
	}

	public static function is_domain($str)
	{
		if (empty($str))
		{
			return false;
		}
		return (bool) preg_match('/^(?:https?://)?[a-z\d]+(?:-[a-z\w]+)*(?:\.[a-z\d]+(?:-[a-z\d]+)*)+$/', $str);
	}

	public static function is_toplevel_domain($str)
	{
		if (empty($str))
		{
			return false;
		}
		return (bool) preg_match('/^(?:https?://)?[a-z\d]+(?:-[a-z\w]+)*\.(?:[a-z\d]+(?:-[a-z\d]+)*)+$/', $str);
	}

	public static function is_subdomain_of($str, $parent)
	{
		if (empty($str) || empty($parent))
		{
			return false;
		}
		return (substr($parent, -strlen($str)) === $str) &&		// Endswith
			(bool) preg_match('/^(?:https?://)?[a-z\d]+(?:-[a-z\d]+)*(?:\.[a-z\d]+(?:-[a-z\d]+)*)+$/', $str);
	}

	public static function is_int($str)
	{
		return (bool) filter_var($str, FILTER_VALIDATE_INT);
	}

	public static function is_float($str)
	{
		return (bool) filter_var($str, FILTER_VALIDATE_FLOAT);
	}

	public static function is_bool($str)
	{
		return (bool) filter_var($str, FILTER_VALIDATE_BOOLEAN);
	}

	public static function is_ip($str)
	{
		return (bool) filter_var($str, FILTER_VALIDATE_IP);
	}

	public static function is_ipv4($str)
	{
		return (bool) filter_var($str, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
	}

	public static function is_ipv6($str)
	{
		return (bool) filter_var($str, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
	}
}