<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 29 Jul 2013
 * Project: maveric
 *
 */
namespace app\models
{
	use app\models\simple\Campaign as SimpleCampaign;

	class Campaign extends SimpleCampaign
	{
		protected $_brand = null;
		protected $_states = null;

		public function __construct($id)
		{
			parent::__construct($id);
			$this->_states = array(
				'pending',
				'active',
				'completed',
				'rejected'
			);
			$this->_brand = new Brand(null);
		}

		public function get()
		{
			if ($t = parent::get())
			{
				$this->_brand->id = $t['brand'];
				$t['brand'] = $this->_brand->get();
			}
			return $t;
		}

		public function create($data)
		{
			if ( ! in_array($data['state'], $this->_states))
			{
				throw new \Exception('Invalid state: '.$data['state']);
			}

			return parent::create($data);
		}

		public function update($data)
		{
			if (isset($data['state']) && ! in_array($data['state'], $this->_states))
			{
				throw new \Exception('Invalid state: '.$data['state']);
			}
			parent::update($data);
		}
	}
}