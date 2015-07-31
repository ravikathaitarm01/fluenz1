<?php
namespace app\controllers\partner
{
	use app\helpers\Json;
	use app\helpers\Secure;
	use app\helpers\UserSession;
	use app\helpers\Url;
	use app\libraries\FormValidator;
	use app\models\Admin;
	use app\models\ExtraUser;
	use app\models\Partner;
	use app\models\notify\NotifyPartnerAccount;
	use app\models\User;

	class Profile extends _Main
	{
		public function __construct()
		{
			parent::__construct(true);
			/*
			if (UserSession::get('user.manager') !== null)
			{
				$this->_403();
			}
			*/
		}

		public function get()
		{
			$user = new Partner(UserSession::get('user._id'));
			$manager = UserSession::get('user.manager')? new ExtraUser(UserSession::get('user.manager._id')): null;
			$this->_display->view(array(
				'main/app/partner/profile.php'
			), array(
				'user' => $user->get(),
				'manager' => $manager->get()
			));
		}

		public function post()
		{
			if ( ! $this->input->is_ajax_request())
			{
				$this->_403();
			}

			if ($this->input->post('action') == 'update-manager')
			{
				$user = new ExtraUser($this->input->post('id'));
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
					$valid->is('Name', $this->input->post('name'))->required()->alnum('- \.')->length(5, 100)->push('name');
					$valid->is('Email', $this->input->post('email'))->required()->email()->length(5, 100)->push('email');
					if ($password)
					{
						$valid->is('Password',  $password)->length(5, 100)->push('password');
					}

					$data = $valid->data();
					$user->update($data);
					if ($password)
					{
						(new NotifyPartnerAccount())->update($uinfo['_id'], UserSession::get('user._id'));
					}

					// Update Session
					UserSession::set('user.manager', $user->get());

					Json::success('User details updated!', null, array('user' => (new User($uinfo['_id']))->get()));
				} catch (\Exception $e) {
					Json::error($e->getMessage());
				}
			}
			else
			{
				$user = new Partner($this->input->post('id'));
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
					$valid->is('Name', $this->input->post('name'))->required()->alnum('- \.')->length(5, 100)->push('name');
					$valid->is('Email', $this->input->post('email'))->required()->email()->length(5, 100)->push('email');
					$valid->is('Contact', $this->input->post('phone'))->required()->length(3, 100)->push('phone');

					$valid->is('Company Name', $this->input->post('company_name'))->required()->length(5, 100)->push('company_name');
					$valid->is('Company Address',  $this->input->post('company_address'))->required()->length(10, 100)->push('company_address');
					$valid->is('Company Website',  $this->input->post('company_url'))->required()->length(5, 100)->push('company_url');

					if ($password)
					{
						$valid->is('Password',  $password)->length(5, 100)->push('password');
					}

					$data = $valid->data();
					$user->update($data);
					if ($password)
					{
						(new NotifyPartnerAccount())->update($uinfo['_id'], UserSession::get('user._id'));
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
}