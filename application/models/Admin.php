<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 29 Jul 2013
 * Project: maveric
 *
 */
namespace app\models
{
	use app\helpers\MongoDoc;
	use app\models\simple\User as SimpleUser;

	class Admin extends SimpleUser
	{
		protected $_type = 'admin';

		public function create($data)
		{
			return parent::create(array(
				'name' => $data['name'],
				'email' => $data['email'],
				'username' => $data['username'],
				'password' => $data['password'],
				'type' => $this->_type,
				'active' => MongoDoc::get($data, 'active', true),
				'last_login' => null
			));
		}
	}
}