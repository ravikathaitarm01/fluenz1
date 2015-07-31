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
	use app\models\simple\User;

	class NotifyBrandAccount extends Notify
	{
		protected $_prefix = 'brand.account.';

		public function register($id, $password, $by=null)
		{
			$user = new User($id);
			$uinfo = $user->get();
			$uinfo['password'] = $password;

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

			$recipients = array(
				$id
			);
			$this->add(array(
				'sender' => $by,
				'recipients' => $recipients,
				'text' => $this->_body($this->_prefix.__FUNCTION__.'.php', array(
					'user' => $uinfo,
					'by'	=> $binfo
				)),
				'type' => $this->_prefix.__FUNCTION__,
				'url' => Url::base('brand')
			));

			$this->mail_enqueue(array(
				'to' => $uinfo['email'],
				'from' => $this->_from_email,
				'subject' => 'User Registered',
				'message' => $this->_mail_body($this->_prefix.__FUNCTION__.'.php', array(
					'user' => $uinfo,
					'by' => $binfo
				))
			));

			// Send notification to admins
			$admin = new Admin(null);
			$emails = array();
			foreach ($admin->filter(array()) as $doc)
			{
				$emails[$doc['email']] = true;
			}

			$this->mail_enqueue(array(
				'to' => array_keys($emails),
				'from' => $this->_from_email,
				'subject' => 'New Brand Registration',
				'message' => $this->_mail_body('admin.'.$this->_prefix.__FUNCTION__.'.php', array(
					'user' => $uinfo,
					'by' => $binfo
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