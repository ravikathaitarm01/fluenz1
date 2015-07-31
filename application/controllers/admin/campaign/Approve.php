<?php
namespace app\controllers\admin\campaign
{
	use app\helpers\Alert;
	use app\helpers\MongoDoc;
	use app\helpers\Url;
	use app\helpers\UserSession;
	use app\libraries\FormValidator;
	use app\models\Brand;
	use app\models\Campaign;
	use app\models\Influencer;
	use app\models\notify\NotifyBrandCampaign;

	class Approve extends _Main
	{
		public function post()
		{
			switch($this->input->post('action'))
			{
				case 'approve':
					$this->_render_approval();
					break;

				case 'finish':
					$this->_finish();
					break;
			}
		}

		protected function _render_approval()
		{
			$id = $this->input->post('id');
			try {
				$campaign = new Campaign(null);
				$cinfo = $campaign->filter_one(array(
					'_id' => new \MongoId($id),
					'state' => array('$in' => array('pending', 'rejected'))
				));

				if ( ! $cinfo)
				{
					Alert::once('error', 'Invalid campaign', Url::referrer());
				}

				$i_sel = array();
				$influencer = new Influencer(null);
				$sel = array();
				foreach (MongoDoc::get($cinfo, 'influencers_select', array()) as $i)
				{
					$i_sel[] = $influencer->filter_one(array('_id' => $i), array(
						'_id' => true,
						'username' => true
					));
					$sel[] = (string)$i;
				}
				$cinfo['influencers_select'] = $i_sel;

				$this->_display->view(array(
					'main/app/admin/campaign/approve.php'
				), array(
					'campaign' => $cinfo,
					'influencers_select_string' => implode(',', $sel),
					'influencers' => (new Influencer(null))->filter(array('active' => true))
				));
			} catch (\Exception $e) {
				Alert::once('error', $e->getMessage(), Url::base('admin/campaign/view/'.$id));
			}
		}

		protected function _finish()
		{
			$id = $this->input->post('id');
			try {
				$selected = array();
				if ($s = $this->input->post('selected_influencers'))
				{
					$selected = explode(',', $s);
				}

				if ( ! $selected)
				{
					throw new \Exception('At least one influencer must be selected');
				}

				foreach ($selected as &$s)
				{
					$s = new \MongoId($s);
				}

				$campaign = new Campaign($id);
				$cinfo = $campaign->filter_one(array(
					'_id' => $campaign->id,
					'state' => array('$in' => array('pending', 'rejected')),
				));

				if ( ! $cinfo)
				{
					throw new \Exception('Invalid Campaign');
				}

				$campaign->update(array(
					'state' => 'active',
					'influencers_select' => $selected,
					'points' => (int)$this->input->post('points'),
					'global' => (bool)$this->input->post('global')
				));
				Alert::once('success', 'Campaign is now active', Url::base('admin/campaign/view/'.$id));
			} catch (\Exception $e) {
				Alert::once('error', $e->getMessage(), Url::base('admin/campaign/view/'.$id));
			}
		}
	}
}