<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 18 Jul 2013
 * Project: maveric
 *
 */

namespace app\helpers
{
	use sys\core\Controller;

	class Alert
	{
		public static function get()
		{
			$v = Controller::instance()->input->session('alert_once');
			Controller::instance()->input->unset_session('alert_once');
			return $v;
		}

		public static function once($type, $message, $redirect=null)
		{
			Controller::instance()->input->set_session('alert_once', array(
				'type' => $type,
				'message' => htmlspecialchars($message)
			));

			if ($redirect)
			{
				Url::redirect($redirect);
			}
		}
	}
}