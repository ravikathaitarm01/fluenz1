<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 29 Jul 2013
 * Project: maveric
 *
 */
namespace app\libraries
{
	use sys\core\Library;

	require_once (APPPATH.'thirdparty/lessphp/lessc.inc.php');

	class LessPHP extends Library
	{
		protected $_less = null;
		protected $_config = null;
		protected $_lock = null;

		public function __construct()
		{
			parent::__construct();
			$this->_config = $this->config->item('site', 'assets', 'less');
			$this->_less = new \lessc;
			$this->_lock = APPPATH.'tmp/lessphp.lock';

			if ( ! file_exists($this->_lock))
			{
				touch($this->_lock);
			}
		}

		public function compile()
		{
			$fp = fopen($this->_lock, 'r+');
			if (flock($fp, LOCK_EX | LOCK_NB))
			{
				foreach ($this->_config as $l)
				{
					$this->_less->checkedCompile($l['input'], $l['output']);
				}
			}
			fclose($fp);
		}
	}
}