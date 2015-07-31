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

	class Pending extends _Main
	{
		public function get()
		{
			$camapaigns = $this->_get_campaigns(array(
				'state' => 'pending'
			));

			$this->_display->view(array(
				'main/app/brand/campaign/pending.php'
			), array(
				'campaigns' => $camapaigns
			));
		}

		public function post()
		{
			try {
				(new NotifyBrandCampaign())->create($id);
				Alert::once('success', 'Campaign queued for admin approval', Url::current());
			} catch (\Exception $e) {
				Alert::once('error', $e->getMessage(), Url::current());
			}
		}
	}
}