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

	class Influencer extends SimpleUser
	{
		protected $_type = 'influencer';

		public function create($data)
		{
			return parent::create(array(
				'name' => $data['name'],
				'email' => $data['email'],
				'username' => $data['username'],
				'password' => $data['password'],
				'phone' => $data['phone'],
				'city' => $data['city'],
				'genre' => $data['genre'],
				'type' => $this->_type,
				'active' => MongoDoc::get($data, 'active', true),
				'last_login' => null
			));
		}
	}
}