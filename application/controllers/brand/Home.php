<?php
namespace app\controllers\brand
{
	class Home extends _Main
	{
		public function index()
		{
			$this->_display->view(array(
				'main/app/brand/home.php'
			));
		}
	}
}