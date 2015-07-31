<?php
namespace app\controllers\brand\social
{
	use app\helpers\Alert;
	use app\helpers\MongoDoc;
	use app\helpers\Url;
	use app\helpers\UserSession;
	use app\libraries\twitter as tw;
	use app\models\Brand;
	use app\models\User;

	class Twitter extends _Main
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
				$sdk = new tw\SDK();
				if ($session = $sdk->obtainToken(Url::base('brand/social/twitter/'.UserSession::get('user._id'))))
				{
					$sdk = new tw\SDK($session['oauth_token'], $session['oauth_token_secret']);
					$account = $session;
					$account['details'] = $sdk->getProfile();
					$brand = new Brand(UserSession::get('user._id'));
					$brand->modify(array(
						'_id' => $brand->id
					),array(
						'$set' => array(
							'social.twitter' => $account
						),
						'$pull' => array(
							'social_invalidated' => 'twitter'
						)
					));
				}
			} catch (tw\TwitterAccessTokenException $e) {
				Alert::once('error', 'Failed to add account: '.$e->getMessage(), Url::base('brand/social'));
			}
			Alert::once('success', 'Account added successfully', Url::base('brand/social'));
		}

		protected function _remove()
		{
			try {
				$brand = new Brand(UserSession::get('user._id'));
				$iinfo = $brand->get();
				if ($social = MongoDoc::get($iinfo, 'social'))
				{
					unset($social['twitter']);
					$brand->update(array(
						'social' => $social
					));
				}

			} catch (\Exception $e) {
				Alert::once('error', 'Failed to remove account: '.$e->getMessage(), Url::base('brand/social'));
			}
			Alert::once('success', 'Account remove successfully', Url::base('brand/social'));
		}
	}
}