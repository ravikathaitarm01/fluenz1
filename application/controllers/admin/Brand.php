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
	use app\models\Brand as BrandModel;
	use app\models\notify\NotifyBrandAccount;

	class Brand extends _Main
	{
		public function get()
		{
			$this->_display->view(array(
				'main/app/admin/brand.php'
			), array(
				'brands' => (new BrandModel(null))->filter(array())
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
			$brand = new BrandModel($this->input->post('id'));
			if ( ! $binfo = $brand->get())
			{
				Json::error('Invalid brand!');
			}

			try {

				$active = $this->input->post('active')? true: false;
				$brand->update(array(
					'active' => $active
				));

				(new NotifyBrandAccount())->activation($binfo['_id'], UserSession::get('user._id'));
				Json::success('Brand status updated!', null);
			} catch (\Exception $e) {
				Json::error($e->getMessage());
			}
		}
	}
}