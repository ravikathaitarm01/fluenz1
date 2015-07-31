<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 29 Jul 2013
 * Project: maveric
 *
 */
namespace app\libraries\klout
{
	use app\helpers\Url;
	use Facebook\Entities\AccessToken;
	use Facebook\GraphObject;
	use sys\core\Library;

	class KloutException extends \Exception {
	}

	class SDK extends Library
	{
		protected $_response;
		protected $_key = null;

		public function __construct()
		{
			parent::__construct();
			$this->input->config->load('social');
			$this->_key = $this->config->item('social', 'klout', 'api-key');
		}

		public function setApiKey($key)
		{
			$this->_key = $key;
		}

		public function getApiKey()
		{
			return $this->_key;
		}

		protected function _api($method, $path, $params=array())
		{
			if ( ! $this->_key)
			{
				throw new KloutException('Invalid API key');
			}

			$c = $this->_curl($method, $path, $params);
			if ($c == 200)
			{
				return $this->_response['data'];
			}
			else if ($c == 404)
			{
				throw new KloutException('Data not found', $c);
			}
			else
			{
				throw new KloutException('Unknown error', $c);
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

		protected function _curl($method, $url, $data=array(), $headers=array())
		{
			$data = array_merge($data, array('key' => $this->_key));

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
				CURLOPT_URL            => 'https://api.klout.com/v2'.$url,
				// process the headers
				CURLOPT_HEADERFUNCTION => array($this, '_curl_header'),
				CURLOPT_HEADER         => false,
				CURLINFO_HEADER_OUT    => true,
			));

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
				throw new KloutException($error, $errno);
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

		public function getIdentityTwitterId($id)
		{
			return $this->_api('GET', "/identity.json/tw/$id");
		}

		public function getIdentityGooglePlusId($id)
		{
			return $this->_api('GET', "/identity.json/gp/$id");
		}

		public function getIdentityInstagramId($id)
		{
			return $this->_api('GET', "/identity.json/ig/$id");
		}

		public function getUser($id)
		{
			return $this->_api('GET', "/user.json/$id");
		}
	}
}