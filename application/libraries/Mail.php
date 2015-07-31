<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 29 Jul 2013
 * Project: maveric
 *
 */
namespace app\libraries
{
	use sys\libraries\Mail as BaseMail;

	class Mail extends BaseMail
	{
		public function __construct()
		{
			parent::__construct();
			$this->config->load('mail');
			$this->_init();
		}
	}
}