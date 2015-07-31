<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 29 Jul 2013
 * Project: maveric
 *
 */
namespace app\libraries\google
{
	use app\helpers\Url;
	use sys\core\Library;

	require_once (APPPATH.'thirdparty/Google/autoload.php');

	class GoogleAccessTokenException extends \Google_Service_Exception {
	}

	class SDK extends Library
	{
		protected $_client = null;

		public function __construct($access_token='', $access_token_secret='')
		{
			parent::__construct();
			$this->input->config->load('social');
			$this->_client = new \Google_Client();
			$this->_client->setApplicationName('Fluenz');
			$this->_client->setClientId($this->config->item('social', 'google', 'client-id'));
			$this->_client->setClientSecret($this->config->item('social', 'google', 'client-secret'));
			$this->_client->setAccessType('offline');
			$this->_client->setApprovalPrompt('force');
			$this->_client->addScope(array(
				\Google_Service_YouTube::YOUTUBE_READONLY,
				\Google_Service_Analytics::ANALYTICS_READONLY,
				\Google_Service_Plus::USERINFO_PROFILE
			));
		}

		public function obtainToken($callback, $scope=array())
		{
			if ($scope)
			{
				$this->_client->setScopes($scope);
			}

			$this->_client->setRedirectUri($callback);
			$r = null;
			if ($this->input->get('code') === null)
			{
				Url::redirect($this->_client->createAuthUrl());
			}
			else
			{
				$this->_client->authenticate($this->input->get('code'));
				return json_decode($this->_client->getAccessToken(), true);
			}
			return null;
		}

		public function getAccessToken()
		{
			$this->_client->getAccessToken();
		}

		public function setRefreshToken($token)
		{
			$this->_client->refreshToken($token);
		}

		/**
		 * @param $name
		 * @return \Google_Service_Analytics|\Google_Service_Plus|\Google_Service_YouTube|null
		 */
		public function getService($name)
		{
			switch ($name)
			{
				case 'youtube':
					return new \Google_Service_YouTube($this->_client);
				case 'plus':
					return new \Google_Service_Plus($this->_client);
				case 'analytics':
					return new \Google_Service_Analytics($this->_client);
			}
			return null;
		}
	}
}