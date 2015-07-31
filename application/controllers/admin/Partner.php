<?php
namespace app\controllers\admin
{
	use app\helpers\Alert;
	use app\helpers\Json;
	use app\helpers\Secure;
	use app\helpers\UserSession;
	use app\helpers\Url;
	use app\libraries\FormValidator;
	use app\models\notify\NotifyPartnerAccount;
	use app\models\Partner as PartnerModel;

	class Partner extends _Main
	{
		public function get()
		{
			$this->_display->view(array(
				'main/app/admin/partner.php'
			), array(
				'partners' => (new PartnerModel(null))->filter(array())->sort(array('_id' => -1))
			));
		}

		public function post()
		{
			switch ($this->input->post('action'))
			{
				case 'activation':
					$this->_activation();
					break;

				case 'create':
					$this->_create();
					break;

				case 'remove':
					$this->_remove();
					break;
			}
		}

		protected function _activation()
		{
			$partner = new PartnerModel($this->input->post('id'));
			if ( ! $binfo = $partner->get())
			{
				Json::error('Invalid partner!');
			}

			try {

				$active = $this->input->post('active')? true: false;
				$partner->update(array(
					'active' => $active
				));

				(new NotifyPartnerAccount())->activation($binfo['_id'], UserSession::get('user._id'));
				Json::success('Partner status updated!', null);
			} catch (\Exception $e) {
				Json::error($e->getMessage());
			}
		}

		protected function _create()
		{
			try {
				$data = $this->_get_user_data();
				$user = new PartnerModel(null);
				$user->id = $user->create(array_replace($data, array('password' => Secure::password($data['password'], $data['username']))));
				(new NotifyPartnerAccount())->register($user->id, $data['password']);
				Alert::once('success', 'Account created successfully!', Url::current());
			} catch (\Exception $e) {
				Alert::once('error', $e->getCode() == 11000? 'Username already exists': $e->getMessage(), Url::current());
			}
		}

		protected function _get_user_data()
		{
			$valid = new FormValidator();

			$valid->is('Name', $this->input->post('name'))->required()->alnum('- \.')->length(5, 100)->push('name');
			$valid->is('Email', $this->input->post('email'))->required()->email()->length(5, 100)->push('email');
			$valid->is('Username', $this->input->post('username'))->required()->alnum('.')->length(5, 100)->push('username');
			$valid->is('Password', $this->input->post('password'))->required()->length(5, 100)->push('password');
			$valid->is('Contact', $this->input->post('phone'))->required()->length(3, 100)->push('phone');

			$valid->is('Company Name', $this->input->post('company_name'))->required()->length(5, 100)->push('company_name');
			$valid->is('Company Address',  $this->input->post('company_address'))->required()->length(10, 100)->push('company_address');
			$valid->is('Company Website',  $this->input->post('company_url'))->required()->length(5, 100)->push('company_url');
			return $valid->data();
		}

		protected function _remove()
		{

			try {
				if ( ! ($id = $this->input->post('id')))
				{
					Alert::once('success', 'Invalid ID!', Url::current());
				}

				$user = new PartnerModel($id);
				$user->remove();
				Alert::once('success', 'Account removed successfully!', Url::current());
			} catch (\Exception $e) {
				Alert::once('error', $e->getMessage(), Url::current());
			}
		}
	}
}