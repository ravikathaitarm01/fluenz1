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

	class Campaign extends Model
	{
		const CLC_CAMPAIGNS = 'campaigns';

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
			$clc = $this->_db->selectCollection(self::CLC_CAMPAIGNS);
			$clc->ensureIndex(array(
				'brand' => 1
			));
			$clc->ensureIndex(array(
				'state' => 1
			));
			$clc->ensureIndex(array(
				'duration.start.timestamp' => -1
			));
			$clc->ensureIndex(array(
				'duration.end.timestamp' => -1
			));
		}

		public function get()
		{
			return $this->_db->selectCollection(self::CLC_CAMPAIGNS)->findOne(array('_id' => $this->id));
		}

		public function create($data)
		{
			$this->_raise_err($this->_db->selectCollection(self::CLC_CAMPAIGNS)->insert($data));
			$this->id = $data['_id'];
			return $this->id;
		}

		public function remove()
		{
			$this->_raise_err($this->_db->selectCollection(self::CLC_CAMPAIGNS)->remove(array(
				'_id' => $this->id
			)));
		}

		public function update($data)
		{
			$this->_raise_err($this->_db->selectCollection(self::CLC_CAMPAIGNS)->update(array(
				'_id' => $this->id
			), array(
				'$set' => $data
			)));
		}

		public function find_modify_one($find, $update, $fields=array())
		{
			return $this->_db->selectCollection(self::CLC_CAMPAIGNS)->findAndModify($find, $update, $fields);
		}

		public function filter_one($find, $fields=array())
		{
			return $this->_db->selectCollection(self::CLC_CAMPAIGNS)->findOne($find, $fields);
		}

		public function filter($find, $fields=array())
		{
			return $this->_db->selectCollection(self::CLC_CAMPAIGNS)->find($find, $fields);
		}

		public function modify($find, $modify, $options=array('multiple'=>true))
		{
			return $this->_db->selectCollection(self::CLC_CAMPAIGNS)->update($find, $modify, $options);
		}

		public function purge($find, $options=array('multiple'=>true))
		{
			return $this->_db->selectCollection(self::CLC_CAMPAIGNS)->remove($find, $options);
		}
	}
}