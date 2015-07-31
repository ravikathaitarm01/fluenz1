<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 29 Jul 2013
 * Project: maveric
 *
 */
namespace app\libraries\vine
{
	use app\helpers\Url;
	use Facebook\Entities\AccessToken;
	use Facebook\GraphObject;
	use sys\core\Library;

	class VineException extends \Exception {
	}

	class SDK extends Library
	{
		protected $_response;
		protected $_session_key = null;
		protected $_username = null;
		protected $_password = null;

		public function __construct($username, $password)
		{
			parent::__construct();
			$this->_username = $username;
			$this->_password = $password;
		}

		public function setSessionKey($key)
		{
			$this->_session_key = $key;
		}

		public function getSessionKey()
		{
			return $this->_session_key;
		}

		protected function _api($method, $path, $params=array())
		{
			if ( ! $this->_session_key)
			{
				if (($c = $this->_curl('POST', '/users/authenticate', array(
					'username' => $this->_username,
					'password' => $this->_password
				))) == 200)
				{
					$this->_session_key = $this->_response['data']['data']['key'];
				}
				else
				{
					$d = $this->_response['data'];
					throw new VineException($d['error'], $d['code']);
				}

				if ($method == '/users/authenticate')
				{
					return $this->_response['data']['data'];
				}
			}

			$c = $this->_curl($method, $path, $params);
			if ($c == 200)
			{
				return $this->_response['data']['data'];
			}
			else
			{
				throw new VineException($this->_response['data']['error'], $this->_response['data']['code']);
			}

		}

		protected function _curl_header($ch, $header)
		{
			$this->_response['raw'] .= $header;

			list($key, $value) = array_pad(explode(':', $header, 2), 2, null);

			$key = trim($key);
			$value = trim($value);

			if ( ! isset($this->_response['headers'][$key])) {
				$this->_response['headers'][$key] = $value;
			} else {
				if (!is_array($this->_response['headers'][$key])) {
					$this->_response['headers'][$key] = array($this->_response['headers'][$key]);
				}
				$this->_response['headers'][$key][] = $value;
			}

			return strlen($header);
		}

		protected function _curl($method, $url, $data=null, $headers=array())
		{
			$this->_response['raw'] = '';
			$c = curl_init();
			curl_setopt($c, CURLOPT_CUSTOMREQUEST, $method);

			if ($method == 'GET' && isset($data))
			{
				$url .= ('?' . http_build_query($data));
			}
			elseif ($method == 'POST' || $method == 'PUT')
			{
				curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($data?:array()));
			}

			curl_setopt_array($c, array(
				CURLOPT_CONNECTTIMEOUT => 30,
				CURLOPT_TIMEOUT        => 10,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SSL_VERIFYPEER => true,
				CURLOPT_SSL_VERIFYHOST => 2,

				CURLOPT_FOLLOWLOCATION => false,
				CURLOPT_URL            => 'https://api.vineapp.com'.$url,
				// process the headers
				CURLOPT_HEADERFUNCTION => array($this, '_curl_header'),
				CURLOPT_HEADER         => false,
				CURLINFO_HEADER_OUT    => true,
			));

			if ($this->_session_key)
			{
				$headers['vine-session-id'] = $this->_session_key;
			}

			if ( ! empty($headers))
			{
				$h = array();
				foreach ($headers as $k => $v)
				{
					$h[] = trim($k . ': ' . $v);
				}
				curl_setopt($c, CURLOPT_HTTPHEADER, $h);
			}


			// do it!
			$response = curl_exec($c);
			$code = curl_getinfo($c, CURLINFO_HTTP_CODE);
			$info = curl_getinfo($c);
			$error = curl_error($c);
			$errno = curl_errno($c);
			curl_close($c);

			if ($errno)
			{
				throw new \Exception($error, $errno);
			}

			$this->_response['data'] = json_decode($response, true);
			//var_dump($info, $headers, $this->_response);die();
			return $code;
		}

		public function userAuth()
		{
			return $this->_api('GET', '/users/authenticate', array(
				'username' => $this->_username,
				'password' => $this->_password
			));
		}

		public function getUserMe()
		{
			return $this->_api('GET', '/users/me');
		}
	}
}