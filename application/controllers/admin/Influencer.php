<?php
namespace app\controllers\admin
{
	use app\helpers\Json;
	use app\helpers\Secure;
	use app\helpers\UserSession;
	use app\helpers\Url;
	use app\libraries\FormValidator;
	use app\models\Admin;
	use app\models\notify\NotifyAdminAccount;
	use app\models\Influencer as InfluencerModel;
	use app\models\notify\NotifyInfluencerAccount;

	class Influencer extends _Main
	{
		public function get()
		{
			$this->_display->view(array(
				'main/app/admin/influencer.php'
			), array(
				'influencers' => (new InfluencerModel(null))->filter(array())
			));
		}

		public function post()
		{
			if (!$this->input->is_ajax_request()) {
				$this->_403();
			}

			switch ($this->input->post('action')) {
				case 'activation':
					$this->_activation();
					break;

				default:
					$this->_403();
			}
		}

		protected function _activation()
		{
			$influencer = new InfluencerModel($this->input->post('id'));
			if ( ! $binfo = $influencer->get())
			{
				Json::error('Invalid influencer!');
			}

			try {

				$active = $this->input->post('active')? true: false;
				$influencer->update(array(
					'active' => $active
				));

				(new NotifyInfluencerAccount())->activation($binfo['_id'], UserSession::get('user._id'));
				Json::success('Influencer status updated!', null);
			} catch (\Exception $e) {
				Json::error($e->getMessage());
			}
		}
	}
}