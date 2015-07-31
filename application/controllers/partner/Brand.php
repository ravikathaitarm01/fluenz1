<?php
namespace app\controllers\partner
{
	use app\helpers\Alert;
	use app\helpers\Json;
	use app\helpers\Secure;
	use app\helpers\UserSession;
	use app\helpers\Url;
	use app\libraries\FormValidator;
	use app\models\notify\NotifyBrandAccount;
	use app\models\notify\NotifyPartnerAccount;
	use app\models\ExtraUser;
	use app\models\Brand as BrandModel;
	use app\models\Partner;

	class Brand extends _Main
	{
		public function get()
		{
			$partner = new Partner(UserSession::get('user._id'));
			$brands = $partner->get_brands();
			$this->_display->view(array(
				'main/app/partner/brand.php'
			), array(
				'brands' => $brands
			));
		}

		public function post()
		{
			switch ($this->input->post('action'))
			{
				case 'create':
					$this->_create();
					break;

				case 'activation':
					$this->_activation();
					break;

				case 'remove':
					$this->_remove();
					break;
			}
		}

		protected function _create()
		{
			Url::redirect('register/'.base64_encode(json_encode(array(
					'type' => 'brand',
					'partner' => array(
						'_id' => (string)UserSession::get('user._id'),
						'name' => UserSession::get('user.name')
					)
				))));
		}

		protected function _get_user_data()
		{
			$valid = new FormValidator();

			$valid->is('Name', $this->input->post('name'))->required()->alnum('- \.')->length(5, 100)->push('name');
			$valid->is('Email', $this->input->post('email'))->required()->email()->length(5, 100)->push('email');
			$valid->is('Username', $this->input->post('username'))->required()->alnum('.')->length(5, 100)->push('username');
			$valid->is('Password', $this->input->post('password'))->required()->length(5, 100)->push('password');
			$valid->is('Account', UserSession::get('user._id'))->required()->push('account');

			return $valid->data();
		}

		protected function _remove()
		{
			try {
				if ( ! ($id = $this->input->post('id')))
				{
					Alert::once('success', 'Invalid ID!', Url::current());
				}

				$user = new BrandModel($id);
				$user->remove();

				$partner = new Partner(UserSession::get('user._id'));
				$partner->remove_brand($id);
				Alert::once('success', 'Brand removed successfully!', Url::current());
			} catch (\Exception $e) {
				Alert::once('error', $e->getMessage(), Url::current());
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