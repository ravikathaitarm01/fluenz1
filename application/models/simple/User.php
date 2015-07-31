<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 29 Jul 2013
 * Project: maveric
 *
 */
namespace app\models\simple
{
	use app\core\Model;

	class User extends Model
	{
		const CLC_USERS = 'users';

		protected $_type = null;
		public $id = null;

		public function __construct($id)
		{
			parent::__construct();
			if ( ! $id instanceof \MongoId)
			{
				$id = new \MongoId($id);
			}
			$this->id = $id;
		}

		public function install()
		{
			$this->_db->selectCollection(self::CLC_USERS)->ensureIndex(array(
				'username' => 1
			), array(
				'unique' => true
			));

			$this->_db->selectCollection(self::CLC_USERS)->ensureIndex(array(
				'type' => 1
			));
		}

		public function get()
		{
			return $this->_db->selectCollection(self::CLC_USERS)->findOne(array('_id' => $this->id));
		}

		public function create($data)
		{
			$this->_raise_err($this->_db->selectCollection(self::CLC_USERS)->insert($data));
			$this->id = $data['_id'];
			return $this->id;
		}

		public function remove()
		{
			$this->_raise_err($this->_db->selectCollection(self::CLC_USERS)->remove(array(
				'_id' => $this->id
			)));
		}

		public function update($data)
		{
			$this->_raise_err($this->_db->selectCollection(self::CLC_USERS)->update(array(
				'_id' => $this->id
			), array(
				'$set' => $data
			)));
		}

		public function filter_one($find, $fields=array())
		{
			return $this->_db->selectCollection(self::CLC_USERS)->findOne($this->_type ? array_replace($find, array('type' => $this->_type)) : $find, $fields);
		}

		public function filter($find, $fields=array())
		{
			return $this->_db->selectCollection(self::CLC_USERS)->find($this->_type ? array_replace($find, array('type' => $this->_type)) : $find, $fields);
		}

		public function modify($find, $modify, $options=array('multiple'=>true))
		{
			return $this->_db->selectCollection(self::CLC_USERS)->update(($this->_type ? array_replace($find, array('type' => $this->_type)) : $find), $modify, $options);
		}

		public function purge($find, $options=array('multiple'=>true))
		{
			return $this->_db->selectCollection(self::CLC_USERS)->remove(($this->_type ? array_replace($find, array('type' => $this->_type)) : $find), $options);
		}
	}
}