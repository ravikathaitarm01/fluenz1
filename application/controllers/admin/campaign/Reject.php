<?php
namespace app\controllers\admin\campaign
{
	use app\helpers\Alert;
	use app\helpers\Json;
	use app\helpers\MongoDoc;
	use app\helpers\Url;
	use app\helpers\UserSession;
	use app\libraries\FormValidator;
	use app\models\Brand;
	use app\models\Campaign;
	use app\models\notify\NotifyBrandCampaign;

	class Reject extends _Main
	{
		public function post()
		{
			try {
				$campaign = new Campaign(null);
				$cinfo = $campaign->find_modify_one(array(
					'_id' => new \MongoId($this->input->post('id')),
					'state' => array('$in' => array('pending', 'active'))
				), array(
					'$set' => array(
						'state' => 'rejected'
					)
				));
				if ( ! $cinfo)
				{
					$this->_403();
				}

				Alert::once('success', 'Campaign has been removed', Url::base('admin/campaign/pending'));
			} catch (\Exception $e) {
				Alert::once('error', $e->getMessage(), Url::referrer());
			}
		}
	}
}