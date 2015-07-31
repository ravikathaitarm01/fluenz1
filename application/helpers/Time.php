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

class Time
{
	public static function since($time, $tz=null, $max_parts=0)
	{
		$f = new \DateTime(date('Y-m-d H:i:s', $time), new \DateTimeZone($tz?:TIMEZONE));
		$time = $f->diff(new \DateTime('now', new \DateTimeZone($tz?:TIMEZONE)))->format('%a days %h hours %i min');
		if (preg_match('/^(?:0 (?:days|hours) )+(.*)/', $time, $m))	// Remove extra 0's
		{
			$time = $m[1];
		}
		if ($max_parts)
		{
			$parts = explode(' ', $time);
			$time = '';
			for ($i=0; $i<$max_parts && $i < count($parts); $i+=2)
			{
				$time .= $parts[$i].' '.$parts[$i+1].' ';
			}
		}
		return trim($time);
	}

	public static function str($time, $tz, $format)
	{

		return (new \DateTime(date('Y-m-d H:i:s', $time), new \DateTimeZone(TIMEZONE)))->setTimezone(new \DateTimeZone($tz?:TIMEZONE))->format($format);
	}
}