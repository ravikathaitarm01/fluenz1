<?php
namespace app\controllers\influencer
{
	use app\controllers\_Main;
	use app\helpers\MongoDoc;
	use app\helpers\UserSession;
	use app\models\Brand;
	use app\models\Influencer;

	class View extends _Main
	{
		public function get($id)
		{
			$favorite = false;
			$lists = array();
			if (UserSession::get('user.type') === 'brand')
			{
				$binfo = (new Brand(UserSession::get('user._id')))->get();
				$favorite = in_array(new \MongoId($id), MongoDoc::get($binfo, 'favorites', array()));
				$lists = MongoDoc::get($binfo, 'lists', array());
			}

			$this->_display->view(array(
				'main/app/influencer/view.php'
			), array(
				'user' => (new Influencer($id))->get(),
				'favorite' => $favorite,
				'lists' => $lists
			));
		}
	}
}