<?php
namespace app\controllers\influencer
{
	use app\controllers\_Main;
	use app\helpers\Json;
	use app\helpers\MongoDoc;
	use app\helpers\UserSession;
	use app\models\Brand;

	class Lists extends _Main
	{
		public function post()
		{
			if (UserSession::get('user.type') !== 'brand')
			{
				$this->_403();
			}

			switch ($this->input->post('action'))
			{
				case 'add':
					$this->_add();
					break;

				default:
					$this->_404();
			}
		}

		protected function _add()
		{
			if ( ! ($id = $this->input->post('id')))
			{
				Json::error('Invalid influencer id');
			}

			$id = new \MongoId($id);
			$brand = new Brand(UserSession::get('user._id'));
			$binfo = $brand->get();
			$lists = MongoDoc::get($binfo, 'lists', array());

			$list_idx = $this->input->post('list');
			$new_list = $this->input->post('new_list');

			if ($list_idx !== null && isset($lists[$list_idx]))
			{
				if (in_array($id, $lists[$list_idx]['influencers']))
				{
					Json::success('Influencer is already preset in the list');

				}
				$lists[$list_idx]['influencers'][] = $id;
			}
			else if ( ! empty($new_list))
			{
				foreach ($lists as $l)
				{
					if ($l['name'] === $new_list)
					{
						Json::error(sprintf('List with name "%s" already exists', $new_list));
					}
				}
				$lists[] = array(
					'name' => $new_list,
					'influencers' => array(
						$id
					)
				);
			}
			else
			{
				Json::error('Invalid list');
			}

			$brand->update(array(
				'lists' => $lists
			));

			Json::success('Success');
		}
	}
}