<?php
namespace app\controllers\admin
{
	use app\controllers\_Main as BaseMain;
	use app\helpers\UserSession;

	class _Main extends BaseMain
	{
		public function __construct($login=true)
		{
			parent::__construct(true);
			if (UserSession::get('user.type') !== 'admin')
			{
				$this->_403();
			}
		}
	}
}