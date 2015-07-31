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
	use app\models\simple\Package as SimplePackage;

	class Brand extends SimpleUser
	{
		protected $_package = null;
		protected $_type = 'brand';

		public function __construct($id)
		{
			parent::__construct($id);
			$this->_package = new SimplePackage();
		}

		public function get()
		{
			if ($t = parent::get())
			{
				$t['package'] = $this->_package->get($t['package']);
			}
			return $t;
		}

		public function create($data)
		{
			if ($this->_package->get($data['package']) === null)
			{
				throw new \Exception('Invalid package: '.$data['package']);
			}

			return parent::create(array(
				'brand_name' => $data['brand_name'],
				'name' => $data['name'],
				'email' => $data['email'],
				'username' => $data['username'],
				'password' => $data['password'],
				'phone' => $data['phone'],
				'package' => $data['package'],
				'referral' => MongoDoc::get($data, 'referral', null),
				'type' => $this->_type,
				'active' => MongoDoc::get($data, 'active', false),
				'social_river' => array(
					'enabled' => MongoDoc::get($data, 'social_river', false),
					'data' => array()
				),
				'last_login' => null
			));
		}

		public function update($data)
		{
			if (isset($data['package']) && $this->_package->get($data['package']) === null)
			{
				throw new \Exception('Invalid package: '.$data['package']);
			}
			parent::update($data);
		}

		public function remove()
		{
			parent::remove();
		}

		public function limit_remaining($key)
		{
			if ($a = $this->get())
			{
				return max(0, MongoDoc::get($a, 'package.limits.'.$key, 0) - MongoDoc::get($a, 'limits.'.$key, 0));
			}

			return 0;
		}

		public function limit_set($key, $value)
		{
			$this->modify(array(
				'_id' => $this->id
			), array(
				'$set' => array(
					'limits.'.$key => $value
				)
			));
		}

		public function limit_inc($key)
		{
			$this->modify(array(
				'_id' => $this->id
			), array(
				'$inc' => array(
					'limits.'.$key => 1
				)
			));
		}

		public function limit_dec($key)
		{
			$this->modify(array(
				'_id' => $this->id
			), array(
				'$inc' => array(
					'limits.'.$key => -1
				)
			));
		}
	}
}