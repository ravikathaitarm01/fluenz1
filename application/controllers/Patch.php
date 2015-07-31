<?php
namespace app\controllers
{
	use app\core\Controller;
	use app\helpers\Url;
	use app\helpers\UserSession;
	use app\models\notify\Notify;
	use app\models\User;

	class Patch extends Controller
	{
		public function __construct()
		{
			parent::__construct();
		}

		public function index()
		{
			$this->_notify();
		}

		protected function _notify()
		{
			// Fix URLs b/w migrations & remove deleted users
			$notify = new Notify();
			foreach ($notify->all() as $n)
			{
				$u = null;
				$uinfo = (new \app\models\simple\User($n['sender']))->get();
				$u = $uinfo['type'];
				if ($u)
				{
					$notify->modify(array(
						'_id' => $n['_id']
					), array(
						'$set' => array('url' => Url::base($u))
					));
				}
				else
				{
					$notify->purge(array(
						'_id' => $n['_id']
					));
				}
			}
		}
	}
}