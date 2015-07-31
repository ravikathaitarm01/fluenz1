<?php
namespace app\controllers
{
	use app\core\Controller;
	use app\models\notify\Notify;
	use app\models\User;

	class Debug extends Controller
	{
		const ROLE_POWER_BASE_ALL = 9;
		const ROLE_POWER_BASE_ACCOUNT = 50;

		public function __construct()
		{
			parent::__construct();
			$this->config->set_item(array('view', 'wrap'), true);
			$this->config->set_item(array('view', 'header'), 'main/common/header.php');
			$this->config->set_item(array('view', 'footer'), 'main/common/footer.php');

			$this->_init();
		}

		public function index($action='')
		{
			switch ($action)
			{
				case 'mail':
					$this->_mails();
					break;

				case 'notification':
					$this->_notifications();
					break;

				case 'db';
					$this->_db();
					break;
			}
		}

		protected function _mails()
		{
			$notify = new Notify();
			$docs = $notify->mail_get_all()->sort(array('_id' => -1));
			//var_dump($docs);
			$this->_display->view('debug/mail.php', array(
				'mails' => $docs
			));
		}

		protected function _notifications()
		{
			$user = new User(null);
			$u = iterator_to_array($user->filter(array()));
			if ($id = $this->input->post('id'))
			{
				$notify = new Notify();
				$this->_display->view('debug/notification.php', array(
					'users' => $u,
					'selected' => $id,
					'notifications' => $notify->get_all(new \MongoId($id))
				));
			}
			else
			{
				$this->_display->view('debug/notification.php', array(
					'users' => $u,
					'selected' => null,
					'notifications' => array()
				));
			}
		}

		protected function _db()
		{
			$db = $this->db->db();
			/*
			foreach (array('facebook_posts', 'facebook_conversations', 'twitter_mentions', 'twitter_searches') as $c)
			{
				$clc = $db->selectCollection($c);
				$clc->update(array(), array(
					'$rename' => array(
						'created_time' => 'created_at'
					)
				),  array('multiple' => true));
			}
			*/
			$clc = $db->selectCollection('statistics_daily');
			foreach ($clc->find(array(
				'social.google.service' => array(
					'$exists' => true
				)
			)) as $d)
			{
				$s = $d['social'];
				unset($s['google']);
				foreach ($d['social']['google']['service'] as $k=>$v)
				{
					if ($k == 'gplus')
					{
						$k = 'plus';
					}
					$s['google-'.$k] = $v;
				}
				$clc->update(array(
					'_id' => $d['_id']
				), array(
					'$set' => array(
						'social' => $s
					)
				));
			}
		}
	}
}