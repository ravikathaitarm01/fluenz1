<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 29 Jul 2013
 * Project: maveric
 *
 */
namespace app\libraries\twitter
{
	use app\helpers\Url;
	use Facebook\Entities\AccessToken;
	use Facebook\GraphObject;
	use sys\core\Library;

	require_once (APPPATH.'thirdparty/tmhOAuth/tmhOAuth.php');

	class TwitterAccessTokenException extends \Exception {
	}

	class SDK extends Library
	{
		protected $_session = null;
		protected $_lib = null;

		public function __construct($access_token='', $access_token_secret='')
		{
			parent::__construct();
			$this->input->config->load('social');
			$this->_lib = new \tmhOAuth(array(
				'consumer_key' => $this->config->item('social', 'twitter', 'consumer-key'),
				'consumer_secret' => $this->config->item('social', 'twitter', 'consumer-secret'),
				'token' => $access_token,
				'secret' => $access_token_secret
			));

			/*
			if ($access_token)
			{
				$this->_lib->config(array(
					'token' => $access_token['token'],
					'secret' => $access_token['secret'],
				));
			}
			*/
		}

		public function obtainToken($callback)
		{
			$r = null;
			if ($this->input->get('oauth_verifier') === null)
			{
				$c = $this->_lib->request('POST', $this->_lib->url('oauth/request_token', ''), array(
					'oauth_callback'     => $callback,
					'x_auth_access_type' => 'write'
				));
				if ($c == 200)
				{
					$r = $this->_lib->extract_params($this->_lib->response['response']);
					if ($r['oauth_callback_confirmed'] == true)
					{
						$this->input->set_session('request_token', $r);
						Url::redirect($this->_lib->url('oauth/authenticate', '').'?oauth_token='.$r['oauth_token']);
					}
				}
				else
				{
					throw new \Exception($this->_lib->response['response'], $c);
				}
			}
			else
			{
				if ( ! $r = $this->input->session('request_token'))
				{
					return null;
				}
				$this->input->unset_session('request_token');

				$this->_lib->reconfigure(array(
					'token' => $r['oauth_token'],
					'secret' => $r['oauth_token_secret'],
				));
				$c = $this->_lib->request('POST', $this->_lib->url('oauth/access_token', ''), array(
					'oauth_verifier' => $this->input->get('oauth_verifier'),
				));
				if ($c == 200)
				{
					$r = $this->_lib->extract_params($this->_lib->response['response']);
				}
			}
			return $r;
		}

		protected function _api($method, $path, $params=array())
		{
			$c = $this->_lib->request($method, $this->_lib->url($path), $params);
			if ($c == 200)
			{
				return json_decode($this->_lib->response['response'], true);
			}
			else if ($c == 401)
			{
				throw new TwitterAccessTokenException('Invalid access token.');
			}
			else
			{
				throw new \Exception($this->_lib->response['response'], $c);
			}
		}

		public function getInfo()
		{
			$r = array(
				'rate-limit' => array(
					'limit' => 0,
					'remaining' => 0,
					'reset' => 0,
				),
				'response-time' => 0
			);
			if (isset($this->_lib->response, $this->_lib->response['headers']))
			{
				foreach (array('limit', 'remaining', 'reset') as $k)
				{
					if (isset($this->_lib->response['headers']['x-rate-limit-'.$k]))
					{
						$r['rate-limit'][$k] = $this->_lib->response['headers']['x-rate-limit-'.$k];
					}
				}
				if (isset($this->_lib->response['headers']['x-response-time']))
				{
					$r['response-time'] = $this->_lib->response['headers']['x-response-time'];
				}
			}
			return $r;
		}

		public function getProfile()
		{
			return $this->_api('GET', '1.1/account/verify_credentials');
		}

		public function getMentions($extra=array())
		{
			return $this->_api('GET', '1.1/statuses/mentions_timeline', $extra);
		}

		public function postTweet($status, $extra=array())
		{
			$r =  $this->_api('POST', '1.1/statuses/update.json', array_merge(array('status' => $status), $extra));
			return $r;
		}

		public function postRetweet($id, $extra=array())
		{
			return $this->_api('POST', "1.1/statuses/retweet/$id.json", $extra);
		}

		public function postFavorite($id, $extra=array())
		{
			return $this->_api('POST', '1.1/favorites/create.json', array_merge(array('id' => $id), $extra));
		}

		public function postFavoriteDestroy($id, $extra=array())
		{
			return $this->_api('POST', '1.1/favorites/destroy.json', array_merge(array('id' => $id), $extra));
		}

		public function getSearchTweets($q, $extra=array())
		{
			$r =  $this->_api('GET', '1.1/search/tweets.json', array_merge(array('q' => $q, 'result_type' => 'recent'), $extra));
			return $r;
		}

		public function getUserTweets($user_id, $extra=array())
		{
			$r =  $this->_api('GET', '1.1/statuses/user_timeline.json', array_merge(array('user_id' => $user_id), $extra));
			return $r;
		}
	}
}