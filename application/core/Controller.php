<?php
namespace app\core
{
	use app\helpers\Alert;
	use app\helpers\Json;
	use app\helpers\MongoDoc;
	use app\helpers\Output;
	use app\helpers\Time;
	use app\helpers\UserSession;
	use sys\core\Controller as BaseController;
	use app\libraries\LessPHP;
	use app\helpers\Url;
	use sys\libraries\Display;

	class Controller extends BaseController
	{
		/**
		 * @var Display
		 */
		protected $_display;

		protected $_session = null;

		public function __construct()
		{
			parent::__construct();
			$less = new LessPHP();
			$less->compile();

			$this->_init();
		}

		protected function _init()
		{
			$this->_display = new Display();
			$this->_display->attach('Input', $this->input, true);
			$this->_display->attach('Url', new Url(), true);
			$this->_display->attach('Time', new Time(), true);
			$this->_display->attach('Alert', new Alert(), true);
			$this->_display->attach('Output', new Output(), true);
			$this->_display->attach('UserSession', new UserSession(), true);
			$this->_display->attach('MongoDoc', new MongoDoc(), true);
			$this->_display->attach('_meta', array(
				'back-url' => $this->input->server('HTTP_REFERER'),
				'current-url' => Url::current(),
				'page-class' => 'none',
				'page-body-class' => '',
				'page-title' => 'Fluenz.io',
				'page-no-menu' => false,
				'page-no-sidebar' => false,
				'page-css' => array(),
				'page-js' => array(),
			));
		}

		protected function _check_login()
		{
			if ( ! UserSession::get('user'))
			{
				if ($this->input->is_ajax_request())
				{
					Json::success('Session has been invalidated. Redirecting to login page...', Url::base('auth/login'));
				}
				else
				{
					Url::redirect('auth/login');
				}
			}
			return true;
		}

		protected function _403($error='')
		{
			if ($this->input->is_ajax_request())
			{
				Json::error('403 Access Denied '. $error);
			}
			$this->_display->view('403.php', array('error' => $error), false, false);
		}

		protected function _404($error='')
		{
			if ($this->input->is_ajax_request())
			{
				Json::error('404 Not Found'. $error);
			}
			$this->_display->view('404.php', array('error' => $error), false, false);
		}
	}
}