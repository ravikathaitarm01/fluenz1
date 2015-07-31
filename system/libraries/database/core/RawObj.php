<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 28 Jul 2013
 * Project: maveric
 *
 */
class RawObj
{
	protected $_obj = null;
	public function __construct($o)
	{
		$this->_obj = $o;
	}

	public function get()
	{
		return $this->_obj;
	}

	public function __toString()
	{
		return (string)$this->_obj;
	}
}


function _r($o)
{
	return new RawObj($o);
}