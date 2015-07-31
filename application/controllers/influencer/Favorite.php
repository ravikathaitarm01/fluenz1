<?php
namespace app\controllers\influencer
{
	use app\controllers\_Main;
	use app\helpers\Json;
	use app\helpers\MongoDoc;
	use app\helpers\UserSession;
	use app\models\Brand;
	use app\models\Influencer;

	class Favorite extends _Main
	{
		public function get()
		{
			if (UserSession::get('user.type') !== 'influencer')
			{
				$this->_403();
			}

			$influencer = new Influencer(UserSession::get('user._id'));
			$iinfo = $influencer->get();

			$brand = new Brand(null);
			$favorites = array();
			$lists = array();

			if ($l = MongoDoc::get($iinfo, 'favorites', null))
			{
				$favorites = $brand->filter(array(
					'_id' => array(
						'$in' => $l
					)
				), array(
					'_id' => true,
					'name' => true,
					'username' => true
				));
			}

			$this->_display->view(array(
				'main/app/influencer/favorite.php'
			), array(
				'favorites' => $favorites,
			));

		}

		public function post()
		{
			if (UserSession::get('user.type') !== 'brand')
			{
				$this->_403();
			}

			if ( ! ($id = $this->input->post('id')))
			{
				Json::error('Invalid influencer id');
			}
			$id = new \MongoId($id);

			$brand = new Brand(UserSession::get('user._id'));
			$binfo = $brand->get();
			$favorites = MongoDoc::get($binfo, 'favorites', array());

			if ($reset = in_array($id, $favorites))
			{
				$favorites = array_values(array_diff($favorites, array($id)));
			}
			else
			{
				$favorites[] = $id;
			}

			$brand->update(array(
				'favorites' => $favorites
			));

			Json::success('Success', null, array('set' => ! $reset));
		}
	}
}