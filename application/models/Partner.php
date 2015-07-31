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
	use app\models\Brand;

	class Partner extends SimpleUser
	{
		protected $_type = 'partner';

		public function create($data)
		{
			return parent::create(array(
				'name' => $data['name'],
				'email' => $data['email'],
				'username' => $data['username'],
				'password' => $data['password'],
				'phone' => $data['phone'],
				'company_name' => $data['company_name'],
				'company_address' => $data['company_address'],
				'company_url' => $data['company_url'],
				'type' => $this->_type,
				'active' => MongoDoc::get($data, 'active', true),
				'last_login' => null
			));
		}

		public function get_brands()
		{
			$brands = array();
			if ($u = $this->get())
			{
				foreach ($u['brands'] as $b)
				{
					$brands[] = (new Brand($b))->get();
				}
			}
			return $brands;
		}

		public function remove_brand($id)
		{
			if ( ! $id instanceof \MongoId)
			{
				$id = new \MongoId($id);
			}
			return $this->modify(array(
				'_id' => $this->id,
			), array(
				'$pull' => array(
					'brand' => $id
				)
			));
		}

		public function valid_brand($id)
		{
			if ( ! $id instanceof \MongoId)
			{
				$id = new \MongoId($id);
			}
			return $this->filter(array(
				'_id' => $this->id,
				'brands' => $id
			))->count() > 0;
		}
	}
}