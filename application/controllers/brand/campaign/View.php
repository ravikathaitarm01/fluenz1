<?php
namespace app\controllers\brand\campaign
{
	use app\helpers\Alert;
	use app\helpers\MongoDoc;
	use app\helpers\Url;
	use app\helpers\UserSession;
	use app\libraries\FormValidator;
	use app\models\Brand;
	use app\models\Campaign;
	use app\models\notify\NotifyBrandCampaign;

	class View extends _Main
	{
		public function get($id)
		{
			$campaign = new Campaign($id);
			$this->_display->view(array(
				'main/app/brand/campaign/view.php'
			), array(
				'campaign' => $campaign->get()
			));
		}
	}
}