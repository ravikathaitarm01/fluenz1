<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 29 Jul 2013
 * Project: maveric
 *
 */
namespace app\helpers;

class Random
{
	public static function password($length, $distribution=null)
	{
		$set = array(
			'lcase' => 'abcdefghijklmnopqrstuvwxyz',
			'ucase' => 'ABCDEFGHIJKLMNOPQRTUVWXYZ',
			'digit' => '1234567890',
			'special' => '@#$%-+=!?><}{',
		);
		// Length distribution
		$len = array(
			'lcase' 	=> ceil($length*0.50),
			'ucase' 	=> ceil($length*0.15),
			'digit' 	=> ceil($length*0.20),
			'special' 	=> ceil($length*0.15),
		);

		if ($distribution)
		{
			$len = $distribution;
		}

		$pwd = '';
		foreach ($set as $k=>$v)
		{
			for ($i=0; $i<$len[$k]; $i++)
			{
				$v = str_shuffle($v);
				$pwd .= $v[0];
			}
			$pwd = str_shuffle($pwd);
		}

		if (($d = (strlen($pwd)-$length)) > 0)
		{
			$pwd = substr(str_shuffle($pwd), 0, $length);
		}
		return $pwd;
	}
}