<?php
namespace app\controllers
{
	use app\core\Controller;
	use app\helpers\UserSession;
	use app\models\notify\Notify;

	class _Main extends Controller
	{
		public function __construct($login=true)
		{
			parent::__construct();
			$this->config->set_item(array('view', 'wrap'), true);
			$this->config->set_item(array('view', 'header'), 'main/common/header.php');
			$this->config->set_item(array('view', 'footer'), 'main/common/footer.php');

			$this->_init();

			if ($login)
			{
				$this->_check_login();
			}

			if (UserSession::get('user'))
			{
				$this->_display->attach('notifications', (new Notify())->get_all(UserSession::get('user._id')));
			}
		}

		protected function _attach_const($key, $cls)
		{
			$this->_display->attach($key, (new \ReflectionClass($cls))->getConstants());
		}

		protected function _on_user($type, $admin, $partner, $influencer, $brand, $else)
		{
			$dict = array(
				'$admin' => $admin,
				'$partner' => $partner,
				'$influencer' => $influencer,
				'$brand' => $brand,
				'$else' => $else
			);

			switch ($type)
			{
				case 'admin':
					if ($else)
					{
						return is_string($admin)? $dict[$admin](): $admin();
					}
					break;
				case '$partner':
					if ($else)
					{
						return is_string($partner)? $dict[$partner](): $partner();
					}
					break;
				case '$influencer':
					if ($else)
					{
						return is_string($influencer)? $dict[$influencer](): $influencer();
					}
					break;
				case '$brand':
					if ($else)
					{
						return is_string($brand)? $dict[$brand](): $brand();
					}
					break;
				default:
					if ($else)
					{
						return is_string($else)? $dict[$else](): $else();
					}
			}
			return null;
		}
	}
}