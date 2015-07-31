<?php
namespace app\controllers\brand
{
	use app\helpers\Alert;
	use app\helpers\Json;
	use app\helpers\MongoDoc;
	use app\helpers\Secure;
	use app\helpers\UserSession;
	use app\helpers\Url;
	use app\libraries\FormValidator;
	use app\models\Brand;
	use app\models\notify\NotifyPartnerAccount;
	use app\models\ExtraUser;

	class River extends _Main
	{
		public function get()
		{
			$user = new Brand(UserSession::get('user._id'));
			$this->_display->view(array(
				'main/app/brand/river.php'
			), array(
				'user' => $user->get()
			));
		}

		public function post()
		{
			switch ($k = $this->input->post('action'))
			{
				case 'facebook':
				case 'twitter':
				case 'instagram':
				case 'google-youtube':
				case 'google-plus':
					$this->_save_river($k);
					break;
			}
		}

		protected function _save_river($key)
		{
			try {
				$user = new Brand(UserSession::get('user._id'));
				$uinfo = $user->get();

				$auto = !! $this->input->post('auto');
				if ($auto)
				{
					if (($r = MongoDoc::get($uinfo, 'social_river.data_custom')) && isset($r[$key]))
					{
						unset($r[$key]);
						$user->update(array(
							'social_river.data_custom' => (object)$r
						));
					}

				}
				else if(($u = $this->input->post('url')) && ! empty($u))
				{
					$user->update(array(
						'social_river.data_custom.'.$key => trim($u)
					));
				}
				Alert::once('success', 'URL updated successfully', Url::current());
			} catch (\Exception $e) {
				Alert::once('error', $e->getMessage(), Url::current());
			}
		}
	}
}