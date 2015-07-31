<?php
namespace app\controllers\brand
{
	use app\controllers\_Main;
	use app\helpers\Json;
	use app\helpers\MongoDoc;
	use app\helpers\Url;
	use app\helpers\UserSession;
	use app\models\Brand;
	use app\models\Influencer;

	class Favorite extends _Main
	{
		public function get()
		{
			if (UserSession::get('user.type') !== 'brand')
			{
				$this->_403();
			}

			$brand = new Brand(UserSession::get('user._id'));
			$binfo = $brand->get();

			$influencer = new Influencer(null);
			$favorites = array();
			$lists = array();

			if ($l = MongoDoc::get($binfo, 'favorites', null))
			{
				$favorites = $influencer->filter(array(
					'_id' => array(
						'$in' => $l
					)
				), array(
					'_id' => true,
					'name' => true,
					'username' => true
				));
			}

			foreach (MongoDoc::get($binfo, 'lists', array()) as $l)
			{
				$lists[] = $l['name'];
			}

			$this->_display->view(array(
				'main/app/brand/favorite.php'
			), array(
				'favorites' => $favorites,
				'lists' => $lists
			));

		}

		public function post()
		{
			if (!$this->input->is_ajax_request()) {
				$this->_403();
			}

			switch ($this->input->post('action')) {
				case 'favorite':
					$this->_favorite();
					break;

				case 'list-view':
					$this->_list_view();
					break;

				case 'list-remove':
					$this->_list_remove();
					break;

				default:
					$this->_403();
			}
		}

		protected function _list_view()
		{
			if (UserSession::get('user.type') !== 'brand')
			{
				$this->_403();
			}

			try {
				$brand = new Brand(UserSession::get('user._id'));
				$binfo = $brand->get();

				if ( ! ($list = MongoDoc::get($binfo, 'lists.'.$this->input->post('list'))))
				{
					Json::error('Invalid list');
				}
				$influencers = (new Influencer(null))->filter(array(
					'_id' => array(
						'$in' => $list['influencers']
					)
				), array(
					'_id' => true,
					'name' => true,
					'username' => true
				));

				$body = $this->_display->view(array(
					'main/app/brand/influencer.list.php'
				), array(
					'influencers' => $influencers,
				), true, false);
				Json::success('Success', null, array('body' => $body));
			} catch (\Exception $e) {
				Json::error($e->getMessage());
			}
		}

		protected function _list_remove()
		{
			if (UserSession::get('user.type') !== 'brand')
			{
				$this->_403();
			}

			try {
				$brand = new Brand(UserSession::get('user._id'));
				$binfo = $brand->get();

				$list_idx = $this->input->post('list');
				if ( ! ($list = MongoDoc::get($binfo, 'lists.'.$list_idx)))
				{
					Json::error('Invalid list');
				}

				$list['influencers'] = array_values(array_diff($list['influencers'], array(new \MongoId($this->input->post('id')))));

				$redirect = null;
				if (count($list['influencers']))
				{
					$binfo['lists'][$list_idx] = $list;
				}
				else
				{
					// Redirect if list is empty, cause we've removed it
					$redirect = Url::base(Url::current());
					unset($binfo['lists'][$list_idx]);
					$binfo['lists'] = array_values($binfo['lists']);
				}

				$brand->update(array(
					'lists' => $binfo['lists']
				));

				$influencers = (new Influencer(null))->filter(array(
					'_id' => array(
						'$in' => $list['influencers']
					)
				), array(
					'_id' => true,
					'name' => true,
					'username' => true
				));

				$body = $this->_display->view(array(
					'main/app/brand/influencer.list.php'
				), array(
					'influencers' => $influencers,
				), true, false);
				Json::success('Success', $redirect, array('body' => $body));
			} catch (\Exception $e) {
				Json::error($e->getMessage());
			}
		}

		protected function _favorite()
		{
			if (UserSession::get('user.type') !== 'influencer')
			{
				$this->_403();
			}

			if ( ! ($id = $this->input->post('id')))
			{
				Json::error('Invalid brand id');
			}
			$id = new \MongoId($id);

			$influencer = new Influencer(UserSession::get('user._id'));
			$iinfo = $influencer->get();
			$favorites = MongoDoc::get($iinfo, 'favorites', array());

			if ($reset = in_array($id, $favorites))
			{
				$favorites = array_values(array_diff($favorites, array($id)));
			}
			else
			{
				$favorites[] = $id;
			}

			$influencer->update(array(
				'favorites' => $favorites
			));

			Json::success('Success', null, array('set' => ! $reset));
		}
	}
}