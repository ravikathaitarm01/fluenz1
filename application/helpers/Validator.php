<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 29 Jul 2013
 * Project: maveric
 *
 */
namespace app\helpers;
use sys\helpers\Validator as BaseValidator;

class Validator extends BaseValidator
{
	public static function is_cloudaccess_subdomain($str, $protocol=false)
	{
		if (empty($str))
		{
			return false;
		}
		$rgx = 'casite-[^.]+\.cloudaccess\.net';
		if ($protocol)
		{
			$rgx = 'https?://'.$rgx;
		}
		return (bool) preg_match('!^'.$rgx.'$!', $str);
	}

	public static function is_toplevel_domain($str, $protocol=false)
	{
		if (empty($str))
		{
			return false;
		}
		$rgx = '[_a-z0-9](?:[_a-z0-9-]*[_a-z0-9])?\.[a-z]+';
		if ($protocol)
		{
			$rgx = 'https?://'.$rgx;
		}
		return (bool) preg_match('!^'.$rgx.'$!', $str);
	}

	public static function is_subdomain($str, $protocol=false)
	{
		if (empty($str))
		{
			return false;
		}
		$rgx = '(?:[_a-z0-9](?:[_a-z0-9-]*[_a-z0-9])?\.)+[_a-z0-9][_a-z0-9-]*[_a-z0-9]\.[a-z]+';
		if ($protocol)
		{
			$rgx = 'https?://'.$rgx;
		}
		return (bool) preg_match('!^'.$rgx.'$!', $str);
	}

	public static function is_domain($str, $protocol=false)
	{
		return (bool) self::is_toplevel_domain($str, $protocol) || self::is_subdomain($str, $protocol);
	}
	
	public static function is_abuse_keyword($str, $keywords) 
    {
		foreach($keywords as $keyword){
		  $pos = strpos($str, $keyword);
		  if ($pos === false) {
				$found = false;
			} else {
				return false;
			}
		}
		if(!$found) 
		{
			return true;
		}
		
	}
}