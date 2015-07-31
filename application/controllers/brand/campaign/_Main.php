<?php
namespace app\controllers\brand\campaign
{
	use app\controllers\brand\_Main as BaseMain;
	use app\helpers\UserSession;
	use app\models\simple\Campaign;

	class _Main extends BaseMain
	{
		protected function _get_campaigns($filter=array())
		{
			$campaign = new Campaign(null);
			$find = array_replace(array(
				'brand' => UserSession::get('user._id')
			), $filter);
			return $campaign->filter($find);
		}
	}
}