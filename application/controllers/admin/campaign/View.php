<?php
namespace app\controllers\admin\campaign
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
			$cinfo = $campaign->get();
			if ( ! $cinfo)
			{
				$this->_404('Invalid campaign');
			}

			$this->_display->view(array(
				'main/app/admin/campaign/view.php'
			), array(
				'campaign' => $cinfo
			));
		}
	}
}