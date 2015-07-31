<?php
namespace app\core
{
	use sys\core\Model as BaseModel;

	class Model extends BaseModel
	{
		protected $_db = null;

		public function __construct()
		{
			parent::__construct();
			$this->_db = $this->db->db();
		}

		protected function _raise_err($r)
		{
			if (isset($r['err']))
			{
				throw new \Exception($r['err'], $r['code']);
			}
			if (isset($r['errmsg']))
			{
				throw new \Exception($r['errmsg'], $r['code']);
			}
			if ($r['ok'] != 1)
			{
				throw new \Exception('Unknown MongoDB error!');
			}
		}
	}
}