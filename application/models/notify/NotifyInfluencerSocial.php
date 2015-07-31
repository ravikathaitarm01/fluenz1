<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 29 Jul 2013
 * Project: maveric
 *
 */
namespace app\models\notify
{
	use app\helpers\Url;
	use app\models\simple\User;

	class NotifyInfluencerSocial extends Notify
	{
		protected $_prefix = 'influencer.social.';

		protected function _social_account($account)
		{
			switch ($account)
			{
				case 'facebook':
					return 'Facebook';
				case 'twitter':
					return 'Twitter';
				case 'instagram':
					return 'Instagram';
				case 'google-youtube':
					return 'YouTube';
				case 'google-analytics':
					return 'Google Analytics';
				case 'google-plus':
					return 'Google+';
				case 'vine':
					return 'Vine';
				case 'klout':
					return 'Klout';
			}
			return null;
		}

		public function invalidate($id, $account)
		{
			$user = new User($id);
			$uinfo = $user->get();

			$user->modify(array(
				'_id' => $user->id
			), array(
				'$addToSet' => array(
					'social_invalidated' => $account
				)
			));

			$recipients = array(
				$id
			);
			$this->add(array(
				'sender' => $id,
				'recipients' => $recipients,
				'text' => $this->_body($this->_prefix.__FUNCTION__.'.php', array(
					'social' => $this->_social_account($account)
				)),
				'type' => $this->_prefix.__FUNCTION__,
				'url' => Url::base('influencer/social')
			));

			$this->mail_enqueue(array(
				'to' => $uinfo['email'],
				'from' => $this->_from_email,
				'subject' => 'Social Account Invalidated',
				'message' => $this->_mail_body($this->_prefix.__FUNCTION__.'.php', array(
					'user' => $uinfo,
					'social' => $this->_social_account($account)
				))
			));
		}
	}
}