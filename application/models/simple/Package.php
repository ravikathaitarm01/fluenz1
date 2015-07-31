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

	class Package extends Model
	{
		const CLC_PACKAGES = 'packages';

		protected $_packages = array(
			'basic',
			'standard',
			'premium',
			'custom'
		);

		protected $_limits = array(
			'outreach-showcase' => array(
				'basic' => true,
				'standard' => true,
				'premium' => true,
				'custom' => null,
			),
			'max-campaigns' => array(
				'basic' => 0,
				'standard' => 1,
				'premium' => 2,
				'custom' => null,
			)
		);

		public function install()
		{
			$clc = $this->_db->selectCollection(self::CLC_PACKAGES);
			$clc->ensureIndex(array(
				'name' => 1
			), array(
				'unique' => true
			));
			foreach ($this->_packages as $p)
			{
				$limits = array();
				foreach ($this->_limits as $k=>$v)
				{
					$limits[$k] = $v[$p];
				}

				if ($d = $clc->findOne(array('name' => $p)))
				{
					$limits = array_replace($limits, $d['limits']);
				}

				$clc->update(array(
					'name' => $p
				), array(
					'$set' => array(
						'limits' => $limits,
					)
				), array(
					'upsert' => true
				));
			}
		}

		public function get($name)
		{
			if ($doc = $this->_db->selectCollection(self::CLC_PACKAGES)->findOne(array(
				'name' => $name
			)))
			{
				return $doc;
			}
			return null;
		}

		public function all()
		{
			return $this->_db->selectCollection(self::CLC_PACKAGES)->find();
		}
	}
}