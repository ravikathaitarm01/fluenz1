<?php
namespace app\controllers
{

	use app\helpers\Alert;
	use app\helpers\Json;
	use app\helpers\MongoDoc;
	use app\helpers\Secure;
	use app\helpers\Url;
	use app\helpers\UserSession;
	use app\libraries\FormValidator;
	use app\models\Partner;
	use app\models\User;


	class Auth extends _Main
	{
		public function __construct()
		{
			parent::__construct(false);
		}

		public function get($action='login')
		{
			if ($action === 'logout')
			{
				$this->_check_login();
				$this->_logout();
			}
			else
			{
				$this->_display->view('main/app/login.php');
			}
		}

		public function post($action='login')
		{
			switch ($action)
			{
				case 'login':
					$this->_login();
					break;

				default:
					Json::error('Invalid action!');
			}
		}

		protected function _set_picture(&$u)
		{
			// Set profile image
			$image = null;
			foreach (array(
						 'picture',
						 'social.facebook.details.picture.url',
						 'social.twitter.details.profile_image_url_https',
					 ) as $t)
			{
				if ($a= MongoDoc::get($u, $t))
				{
					$image = $a;
				}
			}
			$u['picture'] = $image;
		}

		protected function _login()
		{
			if ($this->input->is_ajax_request())
			{
				$login_as = false;
				$u = null;
				if (UserSession::get('user.type') === 'admin')
				{
					$u = (new User($this->input->post('id')))->get();
					$this->_set_picture($u);
					$login_as = true;
				}
				else if (UserSession::get('user.type') === 'partner')
				{
					$partner = new Partner(UserSession::get('user._id'));
					if ($partner->valid_brand($this->input->post('id')))
					{
						$u = (new User($this->input->post('id')))->get();
						$this->_set_picture($u);
						$login_as = true;
					}
				}
				else
				{
					try {
						$data = $this->_get_login_data();
						$user = new User(null);
						if ($u = $user->authenticate($data['username'], Secure::password($data['password'], $data['username'])))
						{
							$this->_set_picture($u);
							if (isset($u['social']))
							{
								unset($u['social']);	// Unset unnecessary social data
							}
							if ($u['type'] === 'extra')
							{
								$t = $u;
								$u = (new User($t['account']))->get();
								$u['manager'] = $t;
							}
						}
					} catch (\Exception $e) {
						Json::error($e->getMessage());
					}
				}

				if ($u)
				{
					$data = array(
						'user' => $u
					);
					if ($login_as)
					{
						// Set the main user, if an existing doesn't exist
						// Only the first user set is main user
						$data['main_user'] = UserSession::get('main_user')?:UserSession::get('user');
					}
					UserSession::set(null, $data);
					Json::success('Login successful! Redirecting to home...', Url::base(''));
				}
				Json::error('Invalid credentials or user not active');
			}
		}

		protected function _logout()
		{
			$this->_check_login();

			// If login as feature is used
			if ($u = UserSession::get('main_user'))
			{
				UserSession::set('user', $u);
				UserSession::erase('main_user');
				Alert::once('warning', 'Logged in as '.$u['username'], Url::base(''));
			}

			UserSession::destroy();
			Alert::once('warning', 'You have been logged out!', Url::base('auth/login'));
		}

		protected function _get_login_data()
		{
			$valid = new FormValidator();
			$valid->is('Username', $this->input->post('username'))->required()->alnum('.')->length(5, 100)->push('username');
			$valid->is('Password', $this->input->post('password'))->required()->length(5, 100)->push('password');
			return $valid->data();
		}
	}
}