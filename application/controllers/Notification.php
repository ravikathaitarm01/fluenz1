<?php
namespace app\controllers
{
	use app\helpers\Url;
	use app\helpers\UserSession;
	use app\libraries\Mail;
	use app\models\notify\Notify;

	class Notification extends _Main
	{
		public function __construct()
		{
			parent::__construct(false);
		}

		public function get($id=null)
		{
			if ($id)
			{
				$this->_view(new \MongoId($id));
			}
			else
			{
				$this->_view_all();
			}
		}

		protected function _view($id)
		{
			$notify = new Notify();
			$n = $notify->get(new \MongoId($id), true, UserSession::get('user._id'));
			Url::redirect(empty($n['url'])? Url::referrer(): $n['url']);
		}

		protected function _view_all()
		{
			$this->_display->view(array(
				'main/app/notification.php',
			), array(
				'notifications' => (new Notify())->get_all(UserSession::get('user._id'))
			));
		}

	}
}