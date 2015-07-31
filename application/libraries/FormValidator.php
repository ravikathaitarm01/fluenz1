<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 29 Jul 2013
 * Project: maveric
 *
 */
namespace app\libraries
{
	use sys\core\Library;

	class FormValidator extends Library
	{
		protected $_key = null;
		protected $_value = null;

		protected $_data = array();
		protected $_flag_bypass = false;

		public function __construct()
		{
			parent::__construct();
		}

		public function clear()
		{
			$this->_data = array();
		}

		public function is($key, $value)
		{
			$this->_key = $key;
			$this->_value = $value;
			$this->_flag_bypass = false;
			return $this;
		}

		public function value()
		{
			return $this->_value;
		}

		public function push($k)
		{
			/*
			if ($this->_flag_bypass)
			{
				return $this;
			}
			*/

			$this->_data[$k] = $this->_value;
			return $this;
		}

		public function data()
		{
			return $this->_data;
		}

		public function transform($func)
		{
			if ($this->_flag_bypass)
			{
				return $this;
			}

			$this->_value = $func($this->_value);
			return $this;
		}

		public function required()
		{
			if ( ! $this->_value)
			{
				throw new \Exception($this->_key.' must not be empty');
			}
			return $this;
		}

		public function optional()
		{
			$this->_flag_bypass = ( ! $this->_value);
			return $this;
		}

		public function length($min=null, $max=null)
		{
			if ($this->_flag_bypass)
			{
				return $this;
			}

			$l = strlen($this->_value);

			if ($min && $l < $min)
			{
				throw new \Exception($this->_key.' must be at least '.$min.' characters long');
			}
			if ($max && $l > $max)
			{
				throw new \Exception($this->_key.' must not exceed '.$max.' characters long');
			}
			return $this;
		}

		public function email()
		{
			if ($this->_flag_bypass)
			{
				return $this;
			}

			if ( ! filter_var($this->_value, FILTER_VALIDATE_EMAIL))
			{
				throw new \Exception($this->_key.' must be a valid email');
			}
			return $this;
		}

		public function integer()
		{
			if ($this->_flag_bypass)
			{
				return $this;
			}

			if ( ! filter_var($this->_value, FILTER_VALIDATE_INT))
			{
				throw new \Exception($this->_key.' must be a valid integer');
			}
			return $this;
		}

		public function alpha($extra='')
		{
			if ($this->_flag_bypass)
			{
				return $this;
			}

			if ( ! preg_match('/^[_a-z'.$extra.']+$/i', $this->_value))
			{
				throw new \Exception($this->_key.' must only contain characters: a-z  and special characters _'.$extra.'.');
			}
			return $this;
		}

		public function alnum($extra='')
		{
			if ($this->_flag_bypass)
			{
				return $this;
			}

			if ( ! preg_match('/^[_a-z0-9'.$extra.']+$/i', $this->_value))
			{
				throw new \Exception($this->_key.' must only contain characters: a-z 0-9 and special characters _'.$extra.'.');
			}
			return $this;
		}

		public function custom($func)
		{
			if ($this->_flag_bypass)
			{
				return $this;
			}

			$func($this->_key, $this->_value);
			return $this;
		}
	}
}