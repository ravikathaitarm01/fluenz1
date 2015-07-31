<?php
namespace app\controllers\admin
{
	use app\models\Admin;
	use app\models\Brand;
	use app\models\Influencer;
	use app\models\Partner;

	class Home extends _Main
	{
		public function index()
		{
			$stats = array(
				'admins' => (new Admin(null))->filter(array())->count(),
				'partners' => (new Partner(null))->filter(array())->count(),
				'brands' => (new Brand(null))->filter(array())->count(),
				'influencers' => (new Influencer(null))->filter(array())->count(),
				'campaigns' => 0
			);

			$this->_display->view(array(
				'main/app/admin/home.php'
			), array(
				'stats' => $stats
			));
		}
	}
}