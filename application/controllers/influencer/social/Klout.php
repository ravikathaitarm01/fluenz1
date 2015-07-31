<?php
namespace app\controllers\influencer\social
{
	use app\helpers\Alert;
	use app\helpers\MongoDoc;
	use app\helpers\Url;
	use app\helpers\UserSession;
	use app\libraries\klout as kl;
	use app\models\Influencer;
	use app\models\User;

	class Klout extends _Main
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
			$accounts = array();
			if (MongoDoc::get($iinfo, 'social.twitter'))
			{
				$accounts['twitter'] = true;
			}
			if (MongoDoc::get($iinfo, 'social.instagram'))
			{
				$accounts['instagram'] = true;
			}
			if (MongoDoc::get($iinfo, 'social.google-plus'))
			{
				$accounts['gplus'] = true;
			}
			$this->_display->view(array(
				'main/app/influencer/social/klout.php',
			), array(
				'accounts' => $accounts
			));
		}

		protected function _save()
		{
			try {
				$influencer = new Influencer(UserSession::get('user._id'));
				$iinfo = $influencer->get();
				$accounts = array();
				if ($a=MongoDoc::get($iinfo, 'social.twitter'))
				{
					$accounts['twitter'] = $a['user_id'];
				}
				if ($a=MongoDoc::get($iinfo, 'social.google-plus'))
				{
					$accounts['gplus'] = $a['id'];
				}
				if ($a=MongoDoc::get($iinfo, 'social.instagram'))
				{
					$accounts['instagram'] = $a['id'];
				}

				$sdk = new kl\SDK();
				$me = null;
				foreach ($accounts as $k=>$id)
				{
					try {
						switch ($k)
						{
							case 'twitter':
								$me = $sdk->getIdentityTwitterId($id);
								break;

							case 'instagram':
								$me = $sdk->getIdentityInstagramId($id);
								break;

							case 'gplus':
								$me = $sdk->getIdentityGooglePlusId($id);
								break;
						}
						break;
					} catch (\Exception $e) {
						$me = null;
					}
				}
				if ( ! $me)
				{
					Alert::once('error', 'Failed to add Klout account: No social accounts were detected on the Klout profile', Url::base('influencer/social'));
				}

				$me = $sdk->getUser($me['id']);
				$influencer->update(array(
					'social.klout' => $me
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
					unset($social['klout']);
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