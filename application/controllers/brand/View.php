<?php
namespace app\controllers\brand
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
			if (UserSession::get('user.type') === 'influencer')
			{
				$iinfo = (new Influencer(UserSession::get('user._id')))->get();
				$favorite = in_array(new \MongoId($id), MongoDoc::get($iinfo, 'favorites', array()));
			}

			$this->_display->view(array(
				'main/app/brand/view.php'
			), array(
				'user' => (new Brand($id))->get(),
				'favorite' => $favorite,
			));
		}
	}
}