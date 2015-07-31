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

	class Analytics extends _Main
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
			try {
				$sdk = new gl\SDK();

				if ($session = $sdk->obtainToken(Url::base('influencer/social/analytics/'), array(\Google_Service_Analytics::ANALYTICS_READONLY)))
				{
					$token = $session['refresh_token'];
					$sdk->setRefreshToken($token);

					$analytics = $sdk->getService('analytics');
					$accounts = array();
					/**
					 * @var \Google_Service_Analytics_Profile $f
					 */
					foreach ($analytics->management_profiles->listManagementProfiles('~all', '~all') as $f)
					{
						$name = $f['accountId'].'-'.$f['webPropertyId'].'-'.$f->getName();
						$accounts[$name] = $f->toSimpleObject();
					}

					$this->_display->view(array(
						'main/app/influencer/social/analytics.php',
					), array(
						'accounts' => $accounts,
						'refresh_token' => $token
					));
				}
			} catch (\Google_Service_Exception $e) {
				Alert::once('error', 'Failed to add account: '.$e->getMessage(), Url::base('influencer/social'));
			}
		}

		protected function _save()
		{
			try {
				$token = $this->input->post('refresh_token');
				$sdk = new gl\SDK();
				$sdk->setRefreshToken($token);
				$analytics = $sdk->getService('analytics');

				$account = json_decode($this->input->post('account'), true);
				$account = (array)$analytics->management_profiles->get($account['accountId'], $account['webPropertyId'], $account['id'])->toSimpleObject();
				$d = (array)$analytics->data_ga->get('ga:'.$account['id'], '30daysAgo', 'yesterday', 'ga:sessions,ga:avgSessionDuration')->toSimpleObject();
				$account['ga_data'] = $d['totalsForAllResults'];

				$account['refresh_token'] = $token;
				var_dump($account);
				$influencer = new Influencer(UserSession::get('user._id'));
				$influencer->modify(array(
					'_id' => $influencer->id
				),array(
					'$set' => array(
						'social.google-analytics' => $account,
					),
					'$pull' => array(
						'social_invalidated' => 'google-analytics'
					)
				));
			} catch (\Exception $e) {
				Alert::once('error', 'Failed to add page: '.$e->getMessage(), Url::base('influencer/social'));
			}
			Alert::once('success', 'Page added successfully', Url::base('influencer/social'));
		}

		protected function _remove()
		{
			try {
				$influencer = new Influencer(UserSession::get('user._id'));
				$iinfo = $influencer->get();
				if ($social = MongoDoc::get($iinfo, 'social'))
				{
					unset($social['google-analytics']);
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