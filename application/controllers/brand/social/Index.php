<?php
namespace app\controllers\brand\social
{
	use app\helpers\UserSession;
	use app\models\Brand;

	class Index extends _Main
	{
		public function get()
		{
			$info = (new Brand(UserSession::get('user._id')))->get();
			$this->_display->view(array(
				'main/app/brand/social/index.php'
			), array(
				'brand' => $info
			));
		}
	}
}