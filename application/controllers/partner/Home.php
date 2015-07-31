<?php
namespace app\controllers\partner
{
	class Home extends _Main
	{
		public function index()
		{
			$this->_display->view(array(
				'main/app/partner/home.php'
			));
		}
	}
}