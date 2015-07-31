<?php
namespace app\controllers\admin\campaign
{
	use app\controllers\admin\_Main as BaseMain;
	use app\helpers\UserSession;
	use app\models\Brand;
	use app\models\Campaign;

	class _Main extends BaseMain
	{
		protected function _get_campaigns($find=array())
		{
			$campaign = new Campaign(null);
			return $campaign->filter($find);
		}
	}
}