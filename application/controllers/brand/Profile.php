<?php
namespace app\controllers\brand
{
	use app\helpers\Json;
	use app\helpers\Secure;
	use app\helpers\UserSession;
	use app\helpers\Url;
	use app\libraries\FormValidator;
	use app\models\Admin;
	use app\models\Brand;
	use app\models\ExtraUser;
	use app\models\notify\NotifyBrandAccount;
	use app\models\User;

	class Profile extends _Main
	{
		public function get()
		{
			$user = new Brand(UserSession::get('user._id'));
			$manager = UserSession::get('user.manager')? (new ExtraUser(UserSession::get('user.manager._id')))->get(): null;

			$this->_display->view(array(
				'main/app/brand/profile.php'
			), array(
				'user' => $user->get(),
				'manager' => $manager
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
					$valid->is('Name', $this->input->post('name'))->required()->length(3, 100)->push('name');
					$valid->is('Email',  $this->input->post('email'))->required()->length(5, 100)->push('email');
					if ($password)
					{
						$valid->is('Password',  $password)->length(5, 100)->push('password');
					}

					$data = $valid->data();
					$user->update($data);
					if ($password)
					{
						(new NotifyBrandAccount())->update($uinfo['_id'], UserSession::get('user._id'));
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
				$user = new Brand($this->input->post('id'));
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
					$valid->is('Contact', $this->input->post('phone'))->required()->length(3, 100)->push('phone');
					if (UserSession::get('main_user.type') == 'admin')
					{
						$valid->is('Package', $this->input->post('package'))->required()->push('package');
					}
					if ($password)
					{
						$valid->is('Password',  $password)->length(5, 100)->push('password');
					}

					$valid->is('About',  $this->input->post('about'))->optional()->length(20, 500)->push('about');
					$valid->is('Address',  $this->input->post('address'))->optional()->length(10, 100)->push('address');
					$valid->is('Wesbite',  $this->input->post('url'))->optional()->length(5)->custom(function($key, $value) {
						$d = get_headers($value, 1);
						if (preg_match('@HTTP/1.1 (4|5)@', $d[0]))
						{
							throw new \Exception($key.' returned a response of : '.$d[0]);
						}
					})->push('url');
					$valid->is('Timezone',  $this->input->post('timezone'))->optional()->length(3, 50)->push('timezone');
					$valid->is('Logo',  $this->input->post('logo'))->optional()->length(5)->custom(function($key, $value) {
						$d = get_headers($value, 1);
						if (preg_match('@HTTP/1.1 (4|5)@', $d[0]))
						{
							throw new \Exception($key.' returned a response of : '.$d[0]);
						}
					})->push('logo');

					$data = $valid->data();

					if (in_array(UserSession::get('main_user.type'), array('admin', 'partner')))
					{
						$data['social_river.enabled'] = !! $this->input->post('social_river');
					}

					$user->update($data);
					if ($password)
					{
						(new NotifyBrandAccount())->update($uinfo['_id'], UserSession::get('user._id'));
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