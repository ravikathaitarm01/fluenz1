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
	use app\models\Admin;
	use app\models\simple\Campaign;
	use app\models\simple\User;

	class NotifyBrandCampaign extends Notify
	{
		protected $_prefix = 'brand.campaign.';

		public function create($id)
		{
			$campaign = new Campaign($id);
			$cinfo = $campaign->get();
			$buser = new User($cinfo['brand']);
			$binfo = $buser->get();


			$this->mail_enqueue(array(
				'to' => $binfo['email'],
				'from' => $this->_from_email,
				'subject' => 'Campaign Created',
				'message' => $this->_mail_body($this->_prefix.__FUNCTION__.'.php', array(
					'user' => $binfo,
					'campaign' => $cinfo
				))
			));

			// Send notification to admins
			$admin = new Admin(null);
			$emails = array();
			$recipients = array();
			foreach ($admin->filter(array()) as $doc)
			{
				$emails[$doc['email']] = true;
				$recipients[] = $doc['_id'];
			}

			$this->add(array(
				'sender' => $binfo['_id'],
				'recipients' => $recipients,
				'text' => $this->_body('admin.'.$this->_prefix.__FUNCTION__.'.php', array(
					'brand' => $binfo
				)),
				'type' => $this->_prefix.__FUNCTION__,
				'url' => Url::base('admin/campaign/view/'.$id)
			));

			$this->mail_enqueue(array(
				'to' => array_keys($emails),
				'from' => $this->_from_email,
				'subject' => 'New Campaign Approval',
				'message' => $this->_mail_body('admin.'.$this->_prefix.__FUNCTION__.'.php', array(
					'brand' => $binfo,
					'campaign' => $cinfo
				))
			));
		}

		public function update($id, $by=null)
		{
			$user = new User($id);
			$uinfo = $user->get();

			$binfo = null;
			if ($by !== null)
			{
				$buser = new User($by);
				$binfo = $buser->get();
			}
			else
			{
				$by = $id;
				$binfo = $uinfo;
			}

			$this->mail_enqueue(array(
				'to' => $uinfo['email'],
				'from' => $this->_from_email,
				'subject' => 'User Updated',
				'message' => $this->_mail_body($this->_prefix.__FUNCTION__.'.php', array(
					'user' => $uinfo,
					'by' => $binfo
				))
			));
		}

		public function activation($id, $by=null)
		{
			$user = new User($id);
			$uinfo = $user->get();

			$binfo = null;
			if ($by !== null)
			{
				$buser = new User($by);
				$binfo = $buser->get();
			}
			else
			{
				$by = $id;
				$binfo = $uinfo;
			}

			$this->mail_enqueue(array(
				'to' => $uinfo['email'],
				'from' => $this->_from_email,
				'subject' => 'Account '. ($uinfo['active']? 'Activated' : 'Deactivated'),
				'message' => $this->_mail_body($this->_prefix.__FUNCTION__.'.php', array(
					'user' => $uinfo,
					'by' => $binfo
				))
			));
		}
	}
}