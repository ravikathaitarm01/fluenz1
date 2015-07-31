<?php
namespace app\controllers\influencer
{
	use app\helpers\Json;
	use app\helpers\Secure;
	use app\helpers\UserSession;
	use app\helpers\Url;
	use app\libraries\FormValidator;
	use app\models\Admin;
	use app\models\notify\NotifyAdminAccount;
	use app\models\Brand as BrandModel;
	use app\models\notify\NotifyInfluencerAccount;

	class Brand extends _Main
	{
		public function get()
		{
			$this->_display->view(array(
				'main/app/influencer/brand.php'
			), array(
				'brands' => (new BrandModel(null))->filter(array())
			));
		}
	}
}