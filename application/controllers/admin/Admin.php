<?php
namespace app\controllers\admin
{
	use app\helpers\Alert;
	use app\helpers\Json;
	use app\helpers\Secure;
	use app\helpers\UserSession;
	use app\helpers\Url;
	use app\libraries\FormValidator;
	use app\models\notify\NotifyAdminAccount;
	use app\models\Admin as AdminModel;

	class Admin extends _Main
	{
		public function get()
		{
			$this->_display->view(array(
				'main/app/admin/admin.php'
			), array(
				'admins' => (new AdminModel(null))->filter(array())->sort(array('_id' => -1))
			));
		}

		public function post()
		{
			switch ($this->input->post('action'))
			{
				case 'create':
					$this->_create();
					break;

				case 'remove':
					$this->_remove();
					break;
			}
		}

		protected function _create()
		{
			try {
				$data = $this->_get_user_data();
				$user = new AdminModel(null);
				$user->id = $user->create(array_replace($data, array('password' => Secure::password($data['password'], $data['username']))));
				(new NotifyAdminAccount())->register($user->id, $data['password']);
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

			return $valid->data();
		}

		protected function _remove()
		{
			if ( ! UserSession::get('user.superadmin'))
			{
				$this->_403();
			}

			try {
				if ( ! ($id = $this->input->post('id')))
				{
					Alert::once('success', 'Invalid ID!', Url::current());
				}

				$user = new AdminModel($id);
				$user->remove();
				Alert::once('success', 'Account removed successfully!', Url::current());
			} catch (\Exception $e) {
				Alert::once('error', $e->getMessage(), Url::current());
			}
		}
	}
}