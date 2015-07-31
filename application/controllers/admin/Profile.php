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
	use app\models\User;

	class Profile extends _Main
	{
		public function get()
		{

			$user = new Admin(UserSession::get('user._id'));
			$this->_display->view(array(
				'main/app/admin/profile.php'
			), array(
				'user' => $user->get()
			));
		}

		public function post()
		{
			if ( ! $this->input->is_ajax_request())
			{
				$this->_403();
			}

			$user = new Admin($this->input->post('id'));
			if ( ! $uinfo = $user->get())
			{
				Json::error('Invalid user!');
			}

			try {

				$password = null;
				if($this->input->post('password'))
				{
					$password = Secure::password($this->input->post('password'), $uinfo['username']);
				}

				$valid = new FormValidator();
				$valid->is('Name', $this->input->post('name'))->required()->length(3, 100)->push('name');
				$valid->is('Email',  $this->input->post('email'))->required()->length(5, 100)->push('email');

				if ($password)
				{
					$valid->is('Password',  $password)->length(5, 100)->push('password');
				}
				$valid->is('Timezone',  $this->input->post('timezone'))->optional()->length(3, 50)->push('timezone');

				$data = $valid->data();
				if (UserSession::get('user.superadmin') && $this->input->post('superadmin') == 1)
				{
					$data['superadmin'] = true;
				}

				$user->update($data);
				if ($password)
				{
					(new NotifyAdminAccount())->update($uinfo['_id'], UserSession::get('user._id'));
				}

				// Update Session
				UserSession::set('user', $user->get());

				Json::success('User details updated!', null, array('user' => (new User($uinfo['_id']))->get()));
			} catch (\Exception $e) {
				Json::error($e->getMessage());
			}
		}
	}
}