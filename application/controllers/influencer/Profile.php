<?php
namespace app\controllers\influencer
{
	use app\helpers\Json;
	use app\helpers\Secure;
	use app\helpers\UserSession;
	use app\helpers\Url;
	use app\libraries\FormValidator;
	use app\models\Admin;
	use app\models\Influencer;
	use app\models\notify\NotifyInfluencerAccount;
	use app\models\User;

	class Profile extends _Main
	{
		public function get()
		{
			$user = new Admin(UserSession::get('user._id'));
			$this->_display->view(array(
				'main/app/influencer/profile.php'
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

			$user = new Influencer($this->input->post('id'));
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
				$valid->is('City', $this->input->post('city'))->required()->push('city');
				$valid->is('Genre', $this->input->post('genre'))->required()->transform(function($d) {
					return explode(',', $d);
				})->push('genre');

				if ($password)
				{
					$valid->is('Password',  $password)->length(5, 100)->push('password');
				}

				$valid->is('About',  $this->input->post('about'))->optional()->length(20, 500)->push('about');
				$valid->is('Address',  $this->input->post('address'))->optional()->length(10, 100)->push('address');
				$valid->is('Date of Birth',  $this->input->post('date_of_birth'))->optional()->custom(function($key, $value) {
					$d = \DateTime::createFromFormat('Y-m-d', $value);
					if ( ! ($d && $d->format('Y-m-d') == $value))
					{
						throw new \Exception($key.' must be a valid date of the format yyyy-mm-dd');
					}
				})->push('date_of_birth');
				$valid->is('Timezone',  $this->input->post('timezone'))->optional()->length(3, 50)->push('timezone');
				$valid->is('Picture',  $this->input->post('picture'))->optional()->length(5)->custom(function($key, $value) {
					$d = get_headers($value, 1);
					if (preg_match('@HTTP/1.1 (4|5)@', $d[0]))
					{
						throw new \Exception($key.' returned a response of : '.$d[0]);
					}
				})->push('picture');
				$valid->is('Interest',  $this->input->post('interest'))->push('interest');

				$data = $valid->data();
				$user->update($data);
				if ($password)
				{
					(new NotifyInfluencerAccount())->update($uinfo['_id'], UserSession::get('user._id'));
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