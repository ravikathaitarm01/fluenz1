<?php
namespace app\controllers
{
	use app\helpers\Json;
	use app\helpers\Secure;
	use app\helpers\Url;
	use app\libraries\FormValidator;
	use app\libraries\Mail;
	use app\models\Brand;
	use app\models\Influencer;
	use app\models\notify\NotifyBrandAccount;
	use app\models\notify\NotifyInfluencerAccount;
	use app\models\Partner;

	class Register extends _Main
	{
		public function __construct()
		{
			parent::__construct(false);
		}

		public function get($extra=null)
		{
			if ($extra)
			{
				$extra = json_decode(base64_decode($extra), true);
			}
			$this->_display->view('main/app/register.php', array(
				'extra' => $extra
			));
		}

		public function post()
		{
			try {
				$data = $this->_get_user_data();
				switch ($this->input->post('user'))
				{
					case 'influencer':
						$user = new Influencer(null);
						$user->id = $user->create(array_replace($data, array('password' => Secure::password($data['password'], $data['username']))));
						(new NotifyInfluencerAccount())->register($user->id, $data['password']);
						Json::success('Account created successfully!');
						break;

					case 'brand':
						$user = new Brand(null);
						// Pre-enable social river
						$data['social_river'] = true;
						$user->id = $user->create(array_replace($data, array('password' => Secure::password($data['password'], $data['username']))));
						if ($p = $this->input->post('partner'))
						{
							$partner = new Partner($p);
							$partner->modify(array(
								'_id' => $partner->id
							), array(
								'$addToSet' => array(
									'brands' => $user->id
								)
							));
						}
						(new NotifyBrandAccount())->register($user->id, $data['password']);
						Json::success('Account created successfully! You will receive a confirmation email when your account has been approved.', Url::base('register'));
						break;
				}
			} catch (\Exception $e) {
				Json::error($e->getCode() == 11000? 'Username already exists': $e->getMessage());
			}
		}

		protected function _get_user_data()
		{
			$valid = new FormValidator();

			switch ($this->input->post('user'))
			{
				case 'influencer':
					$valid->is('Name', $this->input->post('name'))->required()->alnum('- \.')->length(5, 100)->push('name');
					$valid->is('Email', $this->input->post('email'))->required()->email()->length(5, 100)->push('email');
					$valid->is('Contact', $this->input->post('phone'))->required()->length(3, 100)->push('phone');
					$valid->is('City', $this->input->post('city'))->required()->push('city');
					$valid->is('Genre', $this->input->post('genre'))->required()->transform(function($data) {
						return explode(',', $data);
					})->push('genre');

					$valid->is('Username', $this->input->post('username'))->required()->alnum('.')->length(5, 100)->push('username');
					$valid->is('Password', $this->input->post('password'))->required()->length(5, 100)->push('password');
					break;

				case 'brand':
					$valid->is('Brand Name', $this->input->post('brand_name'))->required()->alnum('- ')->length(5, 100)->push('brand_name');
					$valid->is('Package', $this->input->post('package'))->required()->push('package');

					$valid->is('Contact Name', $this->input->post('name'))->required()->length(3, 100)->push('name');
					$valid->is('Contact Number', $this->input->post('phone'))->required()->length(3, 100)->push('phone');
					$valid->is('Contact Email', $this->input->post('email'))->required()->email()->length(5, 100)->push('email');
					$valid->is('Referral', $this->input->post('referral'))->alnum('-')->length(5, 20)->push('referral');

					$valid->is('Username', $this->input->post('username'))->required()->alnum('.')->length(5, 100)->push('username');
					$valid->is('Password', $this->input->post('password'))->required()->length(5, 100)->push('password');
					break;
			}

			return $valid->data();
		}
	}
}