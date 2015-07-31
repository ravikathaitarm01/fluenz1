<?php
namespace app\controllers\brand
{
	use app\helpers\Alert;
	use app\helpers\Json;
	use app\helpers\Secure;
	use app\helpers\UserSession;
	use app\helpers\Url;
	use app\libraries\FormValidator;
	use app\models\notify\NotifyPartnerAccount;
	use app\models\ExtraUser;

	class Manager extends _Main
	{
		public function __construct()
		{
			parent::__construct(true);
			if (UserSession::get('user.manager') !== null)
			{
				$this->_403();
			}
		}

		public function get()
		{
			$this->_display->view(array(
				'main/app/brand/manager.php'
			), array(
				'managers' => (new ExtraUser(null))->filter(array('account' => UserSession::get('user._id')))->sort(array('_id' => -1))
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
				$user = new ExtraUser(null);
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

				$user = new ExtraUser($id);
				$user->remove();
				Alert::once('success', 'Account removed successfully!', Url::current());
			} catch (\Exception $e) {
				Alert::once('error', $e->getMessage(), Url::current());
			}
		}
	}
}