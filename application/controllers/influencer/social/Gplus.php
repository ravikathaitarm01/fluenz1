<?php
namespace app\controllers\influencer\social
{
	use app\helpers\Alert;
	use app\helpers\MongoDoc;
	use app\helpers\Url;
	use app\helpers\UserSession;
	use app\libraries\google as gl;
	use app\models\Influencer;

	class Gplus extends _Main
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

				case 'remove':
					$this->_remove();
					break;
			}
		}

		protected function _attach()
		{
			try {
				$sdk = new gl\SDK();

				if ($session = $sdk->obtainToken(Url::base('influencer/social/gplus/'), array(
					\Google_Service_Plus::PLUS_LOGIN,
					\Google_Service_Plus::PLUS_ME,
					\Google_Service_Plus::USERINFO_PROFILE
				)))
				{
					$token = $session['refresh_token'];
					$sdk->setRefreshToken($token);
					$plus = $sdk->getService('plus');
					$me = (array)$plus->people->get('me')->toSimpleObject();

					$me['refresh_token'] = $token;
					$influencer = new Influencer(UserSession::get('user._id'));
					$influencer->modify(array(
						'_id' => $influencer->id
					),array(
						'$set' => array(
							'social.google-plus' => $me
						),
						'$pull' => array(
							'social_invalidated' => 'google-plus'
						)
					));
				}
			} catch (\Google_Service_Exception $e) {
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
					unset($social['google-gplus']);
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