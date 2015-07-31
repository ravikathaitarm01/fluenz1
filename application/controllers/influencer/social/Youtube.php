<?php
namespace app\controllers\influencer\social
{
	use app\helpers\Alert;
	use app\helpers\MongoDoc;
	use app\helpers\Url;
	use app\helpers\UserSession;
	use app\libraries\google as gl;
	use app\models\Influencer;
	use app\models\User;

	class Youtube extends _Main
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
				$session = null;
				if ($session = $sdk->obtainToken(Url::base('influencer/social/youtube/'), array(\Google_Service_YouTube::YOUTUBE_READONLY)))
				{
					$token = $session['refresh_token'];

					$sdk->setRefreshToken($token);
					$youtube = $sdk->getService('youtube');
					$r = $youtube->channels->listChannels('snippet,statistics', array(
						'mine' => 'true',
					))->getItems();

					if ( ! count($r))
					{
						throw new \Exception('Unable to fetch channel details');
					}

					/**
					 * @var $r \Google_Service_YouTube_ChannelListResponse
					 */
					$r = (array)$r[0]->toSimpleObject();
					$r['refresh_token'] = $token;

					$influencer = new Influencer(UserSession::get('user._id'));
					$influencer->modify(array(
						'_id' => $influencer->id
					),array(
						'$set' => array(
							'social.google-youtube' => $r,
						),
						'$pull' => array(
							'social_invalidated' => 'google-youtube'
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
					unset($social['google-youtube']);
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