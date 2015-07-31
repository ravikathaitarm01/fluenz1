<?php
namespace app\controllers\influencer
{
	use app\helpers\UserSession;
	use app\models\Influencer;

	class Home extends _Main
	{
		public function index()
		{
			$info = (new Influencer(UserSession::get('user._id')))->get();
			$this->_display->view(array(
				'main/app/influencer/home.php'
			), array(
				'influencer' => $info,
				'statistics' => $this->_get_social_differential($info)
			));
		}
	}
}