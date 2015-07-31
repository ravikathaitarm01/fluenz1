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

	class User extends SimpleUser
	{
		public static function type($data)
		{
			switch ($data['type'])
			{
				case 'admin':
					return new Admin($data['_id']);
					break;

				case 'partner':
					return new Partner($data['_id']);
					break;

				case 'influencer':
					return new Influencer($data['_id']);
					break;

				case 'brand':
					return new Brand($data['_id']);
					break;

				case 'extra':
					return new ExtraUser($data['id']);
			}
			return null;
		}

		public function authenticate($username, $password)
		{
			return $this->_db->selectCollection(self::CLC_USERS)->findAndModify(array(
				'username' => $username,
				'password' => $password,
				'active' => true
			), array(
				'$set' => array(
					'last_login' => time()
				)
			));
		}
	}
}