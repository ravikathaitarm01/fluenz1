<?php
namespace app\controllers\influencer
{
	use app\helpers\Alert;
	use app\helpers\Json;
	use app\helpers\MongoDoc;
	use app\helpers\Secure;
	use app\helpers\UserSession;
	use app\helpers\Url;
	use app\libraries\FormValidator;
	use app\models\Brand;
	use app\models\Influencer;
	use app\models\notify\NotifyPartnerAccount;
	use app\models\ExtraUser;

	class River extends _Main
	{
		public function get($brand_id=null)
		{
			$user = new Influencer(UserSession::get('user._id'));
			$uinfo = $user->get();
			$brands = array();
			$bmodel = new Brand(null);
			foreach (MongoDoc::get($uinfo, 'favorites', array()) as $b)
			{
				$brands[] = $bmodel->filter_one(array(
					'_id' => $b
				), array(
					'_id' => true,
					'name' => true,
					'username' => true
				));
			}
			$river = array();
			if ($brands && ! $brand_id)
			{
				$brand_id = $brands[0]['_id'];
			}

			if ($brand_id)
			{
				$river = MongoDoc::get((new Brand($brand_id))->get(), 'social_river', array());
			}
			$this->_display->view(array(
				'main/app/influencer/river.php'
			), array(
				'brands' => $brands,
				'brand_id' => $brand_id,
				'river' => $river
			));
		}

		public function post()
		{
			Url::redirect(Url::base('influencer/river/'.$this->input->post('id')?:''));
		}
	}
}