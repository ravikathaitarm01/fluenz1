<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 19 Jul 2013
 * Project: maveric
 *
 */

namespace sys\libraries
{
	// Re-Register(prepend) Smarty3-Autoload so it triggers before our own autoload
	// spl_autoload_register('smartyAutoload', true, true);

	/**
	 * Include the Smarty Library
	 */
	//require_once BASEPATH.'thirdparty/smarty3/Smarty.class.php';
	require_once ROOT.'includes/classes/Smarty/Smarty.class.php';

	use sys\core\Config;
	use sys\core\Exception;
	use sys\core\Library;
	use \Smarty as BaseSmarty;
	use \WHMCS_Smarty as BaseSmartyWHMCS;

	class Smarty extends Library
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
		 * The smarty object
		 * @var null|\Smarty
		 */
		protected $_smarty = null;


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

		public function __construct()
		{
			parent::__construct();

			$this->config->load('smarty.php');
			$this->compile_dir = $this->config->item('smarty', 'compile_dir')?:APPPATH.'var/template_c';
			$this->template_dir = $this->config->item('smarty', 'template_dir')?:APPPATH.'views/templates';
			$this->_wrap = $this->config->item('smarty', 'wrap')?:false;
			$this->_ext_vars = array();

			// Test the compiled templates directory, create if necessary
			file_exists($this->compile_dir) OR mkdir($this->compile_dir, DIR_WRITE_MODE, true);

			// Test the template directory
			if ( ! is_dir($this->template_dir))
			{
				Exception::error('Template directory does not exist');
			}

			// Assign some common variable to smarty
			$this->_smarty = new BaseSmartyWHMCS();	// WHMCS version of smarty
			$this->_smarty->caching = $this->config->item('smarty', 'caching')?:0;
			$this->_smarty->debugging = $this->config->item('smarty', 'debugging')?:true;
			$this->_smarty->compile_dir = $this->compile_dir;
			$this->_smarty->template_dir = $this->template_dir;
			$this->_smarty->assign( 'APPPATH', APPPATH );
			$this->_smarty->assign( 'BASEPATH', BASEPATH );

			$this->log->write('debug', "Smarty Class Initialized");
		}

		/**
		 * Renders the smarty template
		 * @param string|array $template Path to smarty templates to render
		 * @param array $data Params to be passed to smarty
		 * @param bool $return If set to true, the output is returned
		 * @return string
		 */
		public function view($template, $data = array(), $return = false)
		{
			foreach (array_merge($this->_ext_vars, $data) as $key => $val)
			{
				$this->_smarty->assign($key, $val);
			}


			if ( ! is_array($template))
			{
				$template = array($template);
			}

			if ($this->_wrap)
			{
				array_unshift($template, $this->config->item('smarty', 'header')?:'header.tpl');
				$template[] = $this->config->item('smarty', 'footer')?:'footer.tpl';
			}

			$out = '';
			foreach ($template as $t)
			{
				//echo PHP_EOL, 'E', $t, PHP_EOL;
				$out .= $this->_smarty->fetch($this->_template($t));
				//echo $this->_smarty->fetch($t);
				//echo PHP_EOL, 'D -----------', PHP_EOL;
			}

			if ($return == false)
			{

				echo $out;
			}
			else
			{
				return $out;
			}
		}

		public function attach($key, $value=null)
		{
			if (is_array($key))
			{
				$this->_ext_vars = array_merge($this->_ext_vars, $key);
			}
			else
			{
				$this->_ext_vars[$key] = $value;
			}
		}

		public function get_var($key)
		{
			if ($key === null)
			{
				return $this->_ext_vars;
			}
			else if (isset($this->_ext_vars[$key]))
			{
				return $this->_ext_vars[$key];
			}

			return null;
		}

		protected function _template($name)
		{
			return $name;
		}
	}
}