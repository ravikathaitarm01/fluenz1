<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 29 Jul 2013
 * Project: maveric
 *
 */
namespace app\libraries\instagram
{
	use app\helpers\Url;
	use sys\core\Library;

	require_once (APPPATH.'thirdparty/instagram/Instagram.php');

	use MetzWeb\Instagram;

	class InstagramAccessTokenException extends \Exception {
	}

	class SDK extends Library
	{
		protected $_lib = null;

		public function __construct($access_token='', $access_token_secret='')
		{
			parent::__construct();
			$this->input->config->load('social');
			$this->_lib = new Instagram(array(
				'apiKey' => $this->config->item('social', 'instagram', 'client-id'),
				'apiSecret' => $this->config->item('social', 'instagram', 'client-secret'),
				'apiCallback' => '',
			));
		}

		public function obtainToken($callback)
		{
			$this->_lib->setApiCallback($callback);
			$r = null;
			if ($this->input->get('code') === null)
			{
				Url::redirect($this->_lib->getLoginUrl());
			}
			else
			{
				$r =  json_decode(json_encode($this->_lib->getOAuthToken($this->input->get('code'))), true);
				if (isset($r['code']) && $r['code'] !== 200)
				{
					throw new InstagramAccessTokenException($r['error_type']. ' - '. $r['error_message']);
				}
				return $r;
			}
			return null;
		}

		public function setAccessToken($token)
		{
			$this->_lib->setAccessToken($token);
		}

		protected function _api($function, $params = null, $auth = false, $method = 'GET')
		{
			$r = json_decode(json_encode($this->_lib->_makeCall($function, $auth, $params, $method)), true);

			if ($r['meta']['code'] == 200)
			{
				return $r['data'];
			}
			else if ($r['meta']['code'] == 400)
			{
				throw new InstagramAccessTokenException('Invalid access token.');
			}
			else
			{
				throw new \Exception($r['meta']['error_message'], $r['meta']['code']);
			}
		}

		public function getUser($id)
		{
			return $this->_api("users/$id", null, true);
		}

		public function getUserMedia($id, $extra=null)
		{
			return $this->_api("users/$id/media/recent", $extra, true);
		}
	}
}