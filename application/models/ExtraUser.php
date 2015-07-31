<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 29 Jul 2013
 * Project: maveric
 *
 */
namespace app\models
{
	use app\models\simple\User as SimpleUser;

	class ExtraUser extends SimpleUser
	{
		protected $_type = 'extra';

		public function get()
		{
			$data = parent::get();
			$user = (new SimpleUser($data['account']))->get();
			$data['type'] = $user['type'];
			return array_replace($user, $data);
		}

		public function create($data)
		{
			return parent::create(array(
				'name' => $data['name'],
				'email' => $data['email'],
				'username' => $data['username'],
				'password' => $data['password'],
				'account' => $data['account'],
				'type' => $this->_type,
				'active' => true,
				'last_login' => null
			));
		}
	}
}