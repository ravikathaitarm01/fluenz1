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

	class Index extends _Main
	{
		public function get($state=null)
		{
			if ( ! $state)
			{
				Url::redirect('admin/campaign/pending');
			}

			$filter = array();
			if ($state != 'all')
			{
				$filter = array(
					'state' => $state
				);
			}

			$campaigns = $this->_get_campaigns($filter)->sort(array(
				'_id' => 1
			));

			$r = array();
			foreach ($campaigns as $c)
			{
				$brand = new Brand($c['brand']);
				$c['brand'] = $brand->filter_one(array(
					'_id' => $c['brand']
				), array(
					'_id' => true,
					'username' => true
				));
				$r[] = $c;
			}

			$this->_display->view(array(
				'main/app/admin/campaign/index.php'
			), array(
				'campaigns' => $r
			));
		}
	}
}