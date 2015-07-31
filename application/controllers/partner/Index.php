<?php
namespace app\controllers\partner
{
	use app\helpers\Url;
	use app\helpers\UserSession;

	class Index extends _Main
	{
		public function index()
		{
			Url::redirect(UserSession::get('user.type').'/home');
		}
	}
}