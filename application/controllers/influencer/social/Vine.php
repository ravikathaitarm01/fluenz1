<?php
namespace app\controllers\influencer\social
{
	use app\helpers\Alert;
	use app\helpers\MongoDoc;
	use app\helpers\Url;
	use app\helpers\UserSession;
	use app\libraries\vine as vn;
	use app\models\Influencer;
	use app\models\User;

	class Vine extends _Main
	{
		public function get()
		{
			$this->_attach();
		}

		public function post()
		{
			switch ($this->input->post('action'))
			{
				case 'attach':
					$this->_attach();
					break;

				case 'save':
					$this->_save();
					break;

				case 'remove':
					$this->_remove();
					break;
			}
		}

		protected function _attach()
		{
			$influencer = new Influencer(UserSession::get('user._id'));
			$iinfo = $influencer->get();
			$creds = array(
				'username' => '',
				'password' => ''
			);
			if ($c = MongoDoc::get($iinfo, 'social.vine.credentials'))
			{
				$creds = $c;
			}

			$this->_display->view(array(
				'main/app/influencer/social/vine.php',
			), array(
				'vine_creds' => $creds
			));
		}

		protected function _save()
		{
			try {
				$sdk = new vn\SDK($this->input->post('username'), $this->input->post('password'));
				$me = $sdk->getUserMe();
				$me['credentials'] = array(
					'username' => $this->input->post('username'),
					'password' => $this->input->post('password')
				);
				$influencer = new Influencer(UserSession::get('user._id'));

				$influencer->modify(array(
					'_id' => $influencer->id
				),array(
					'$set' => array(
						'social.vine' => $me
					),
					'$pull' => array(
						'social_invalidated' => 'vine'
					)
				));
			} catch (\Exception $e) {
				Alert::once('error', 'Failed to add account: '.$e->getMessage(), Url::base('influencer/social'));
			}
			Alert::once('success', 'Account added successfully', Url::base('influencer/social'));
		}

		protected function _remove()
		{
			try {
				$influencer = new Influencer(UserSession::get('user._id'));
				$iinfo = $influencer->get();
				if ($social = MongoDoc::get($iinfo, 'social'))
				{
					unset($social['vine']);
					$influencer->update(array(
						'social' => $social
					));
				}

			} catch (\Exception $e) {
				Alert::once('error', 'Failed to remove account: '.$e->getMessage(), Url::base('influencer/social'));
			}
			Alert::once('success', 'Account remove successfully', Url::base('influencer/social'));
		}
	}
}