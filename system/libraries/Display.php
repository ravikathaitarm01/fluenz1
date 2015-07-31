<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 19 Jul 2013
 * Project: maveric
 *
 */

namespace sys\libraries
{
	use sys\core\Config;
	use sys\core\Exception;
	use sys\core\Library;

	class Display extends Library
	{
		/**
		 * Singleton instance
		 * @var null|Smarty
		 */
		protected static $_instance = null;

		/**
		 * Returns the singleton instance for the class
		 * @return Smarty
		 */
		public static function instance()
		{
			if (self::$_instance === null)
			{
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Wrap the template with header & footer
		 * @var bool
		 */
		protected $_wrap = false;

		/**
		 * The vars to attach to template
		 * @var string
		 */
		protected $_ext_vars = false;
		
		protected $_meta_vars = array();

		public function __construct()
		{
			parent::__construct();

			$this->config->load('view.php');
			$this->template_dir = $this->config->item('view', 'template_dir')?:APPPATH.'views/templates';
			$this->_wrap = $this->config->item('view', 'wrap')?:false;
			$this->_ext_vars = array();


			// Test the template directory
			if ( ! is_dir($this->template_dir))
			{
				Exception::error('Template directory does not exist');
			}
		}

		/**
		 * Renders the smarty template
		 * @param string|array $template Path to smarty templates to render
		 * @param array $data Params to be passed to smarty
		 * @param bool $return If set to true, the output is returned
		 * @param bool $wrap If set to true, template wrapping is used if present
		 * @return string
		 */
		public function view($template, $data = array(), $return = false, $wrap = true)
		{
			ob_start();
			extract(array_merge(array(
				'data' => array_replace_recursive($this->_ext_vars, $data)
			), $this->_meta_vars));

			if ( ! is_array($template))
			{
				$template = array($template);
			}
			$out = '';
			foreach ($template as $t)
			{
				include($this->_template($t));
			}

			$out = ob_get_clean();

			if ($wrap && $this->_wrap)
			{
				ob_start();
				if (isset($data['_this']))
				{
					$data['_meta'] = array_replace_recursive($data['_meta'], $data['_this']);
				}
				include($this->_template($this->config->item('view', 'header')?:'header.php'));
				echo $out;
				include($this->_template($this->config->item('view', 'footer')?:'footer.php'));
				$out = ob_get_clean();
			}

			
			if ($return == false)
			{
				echo $out;
				return null;
			}
			else
			{
				return $out;
			}
		}

		public function attach($key, $value=null, $meta=false)
		{
			if (is_array($key))
			{
				if ($meta)
				{
					$this->_meta_vars = array_merge($this->_meta_vars, $key);
				}
				else
				{
					$this->_ext_vars = array_merge($this->_ext_vars, $key);
				}
			}
			else
			{
				if ($meta)
				{
					$this->_meta_vars[$key] = $value;
				}
				else
				{
					$this->_ext_vars[$key] = $value;
				}
			}
		}

		public function get_var($key, $meta=false)
		{
			$container = $this->_ext_vars;
			if ($meta)
			{
				$container = $this->_meta_vars;
			}

			if ($key === null)
			{
				return $container;
			}
			else if (isset($container[$key]))
			{
				return $container[$key];
			}

			return null;
		}

		protected function _template($name)
		{
			return $this->template_dir.DIRECTORY_SEPARATOR.$name;
		}
	}
}
