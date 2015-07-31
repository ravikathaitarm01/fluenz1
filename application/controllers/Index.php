<?php
namespace app\controllers
{
	use app\core\Controller;
	use app\helpers\Url;
	use app\helpers\UserSession;
	use app\models\Brand;
	use app\models\User;

	class Index extends _Main
	{
		public function index()
		{
			Url::redirect(UserSession::get('user.type').'/home');
		}
	}
}