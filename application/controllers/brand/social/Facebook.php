<?php
namespace app\controllers\brand\social
{
	use app\helpers\Alert;
	use app\helpers\MongoDoc;
	use app\helpers\Url;
	use app\helpers\UserSession;
	use app\libraries\facebook as fb;
	use app\models\Brand;
	use app\models\User;

	class Facebook extends _Main
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
				$sdk = new fb\SDK();
				if ($session = $sdk->obtainToken(Url::base('brand/social/facebook/'.UserSession::get('user._id'))))
				{
					$token = $session->getAccessToken()->extend();
					$sdk = new fb\SDK($token);
					$profile = $sdk->getProfile();
					$pid = $profile->getId();
					$r = $sdk->getAccounts($pid)->asArray();
					$this->_display->view(array(
						'main/app/brand/social/facebook.php',
					), array(
						'pages' => $r['data'],
					));
				}
			} catch (fb\FacebookAccessTokenException $e) {
				Alert::once('error', 'Failed to add page: '.$e->getMessage(), Url::base('brand/social'));
			}
		}

		protected function _save()
		{
			try {
				$page = json_decode($this->input->post('page_data'), true);

				$sdk = new fb\SDK($page['access_token']);
				$page['details'] = $sdk->getPageDetails($page['id'])->asArray();
				$brand = new Brand(UserSession::get('user._id'));
				$insight = json_decode(json_encode($sdk->getInsight($page['id'], 'page_engaged_users', 'week')->asArray()), true);
				$t = MongoDoc::get($insight, 'data.0.values', array(array('value' => 0)));
				$t = $t[count($t)-1]['value'];
				$page['insights'] = array(
					'engaged_users' => $t
				);

				$brand->modify(array(
					'_id' => $brand->id
				),array(
					'$set' => array(
						'social.facebook' => $page
					),
					'$pull' => array(
						'social_invalidated' => 'facebook'
					)
				));

			} catch (\Exception $e) {
				if ($e instanceof fb\FacebookAccessTokenException)
				{
					//$brand->invalidate('facebook.page', UserSession::get('user'));
					//(new NotifyNetwork())->invalidate('facebook.page', $brand->get(), UserSession::get('user'));
				}
				Alert::once('error', 'Failed to add page: '.$e->getMessage(), Url::base('brand/social'));
			}
			Alert::once('success', 'Page added successfully', Url::base('brand/social'));
		}

		protected function _remove()
		{
			try {
				$brand = new Brand(UserSession::get('user._id'));
				$iinfo = $brand->get();
				if ($social = MongoDoc::get($iinfo, 'social'))
				{
					unset($social['facebook']);
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