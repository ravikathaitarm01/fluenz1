<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 29 Jul 2013
 * Project: maveric
 *
 */
namespace app\models
{
	use app\core\Model;

	class Statistics extends Model
	{
		const CLC_STATISTICS_DAILY = 'statistics_daily';

		protected  function _id($id)
		{
			if ( ! $id instanceof \MongoId)
			{
				$id = new \MongoId($id);
			}
			return $id;
		}

		public function install()
		{
			$this->_db->selectCollection(self::CLC_STATISTICS_DAILY)->ensureIndex(array(
				'user_id' => 1,
				'date' => 1,
			), array(
				'unique' => true
			));

			$this->_db->selectCollection(self::CLC_STATISTICS_DAILY)->ensureIndex(array(
				'type' => 1
			));
		}

		public function get($id, $date)
		{
			return $this->_db->selectCollection(self::CLC_STATISTICS_DAILY)->findOne(array(
				'user_id' => $this->_id($id),
				'date' => $date
			));
		}

		public function insert($id, $date, $data)
		{
			$data['user_id'] = $this->_id($id);
			$data['date'] = $date;
			$this->_raise_err($this->_db->selectCollection(self::CLC_STATISTICS_DAILY)->insert($data));
			return $data['_id'];
		}

		public function save($id, $date, $data)
		{
			$data['user_id'] = $this->_id($id);
			$data['date'] = $date;
			$this->_raise_err($this->_db->selectCollection(self::CLC_STATISTICS_DAILY)->update(array(
				'user_id' => $data['user_id'],
				'date' => $date
			), array(
				'$set' => $data
			), array(
				'upsert' => true
			)));
			return true;
		}
	}
}