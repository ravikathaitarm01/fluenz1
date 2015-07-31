<?php
namespace app\controllers\influencer\social
{
	use app\helpers\UserSession;
	use app\models\Influencer;

	class Index extends _Main
	{
		public function get()
		{
			$info = (new Influencer(UserSession::get('user._id')))->get();
			$this->_display->view(array(
				'main/app/influencer/social/index.php'
			), array(
				'influencer' => $info,
				'statistics' => $this->_get_social_differential($info)
			));
		}
	}
}