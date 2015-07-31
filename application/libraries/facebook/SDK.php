<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 29 Jul 2013
 * Project: maveric
 *
 */
namespace app\libraries\facebook
{
	use app\helpers\Url;
	use Facebook\Entities\AccessToken;
	use Facebook\FacebookPermissionException;
	use Facebook\GraphObject;
	use sys\core\Library;

	require_once (APPPATH.'thirdparty/facebook_sdk/autoload.php');

	use Facebook\FacebookSession;
	use Facebook\FacebookRequest;
	use Facebook\GraphUser;
	use Facebook\FacebookRequestException;
	use Facebook\FacebookRedirectLoginHelper;

	class FacebookAccessTokenException extends \Exception {
	}

	class SDK extends Library
	{
		protected $_session = null;

		public function __construct($access_token=null, $is_page=false)
		{
			parent::__construct();
			$this->input->config->load('social');
			FacebookSession::setDefaultApplication($this->config->item('social', 'facebook', 'app-id'), $this->config->item('social', 'facebook', 'app-secret'));
			if ($access_token !== null)
			{
				if( ! (new FacebookSession($access_token))->getAccessToken()->isValid())
				{
					throw new FacebookAccessTokenException('Token is not valid anymore.');
				}

				/*
				if ( ! $is_page)
				{
					try {
						$code = AccessToken::getCodeFromAccessToken($access_token);
						$access_token = AccessToken::getAccessTokenFromCode($code);
					} catch(\Exception $e) {
						throw new FacebookAccessTokenException('Unable to obtain fresh access token.');
					}
				}
				*/


				$this->_session = new FacebookSession($access_token);
			}
		}

		public function obtainToken($callback)
		{
			$helper = new FacebookRedirectLoginHelper($callback);
			if ($this->input->get('code') === null)
			{
				/**
				 * Required permissions
				 * public_profile
				 * email
				 * read_insights		Show analytics data of page in dashboard
				 * read_page_mailboxes	Manage page conversations
				 * read_mailbox			Manage page mailbox
				 * manage_pages			Manage brand pages
				 * publish_pages		Take actions on behalf of page
				 */
				$loginUrl = $helper->getLoginUrl(array(
					/*
					'email',
					'user_likes',
					'user_birthday',
					'user_hometown',
					'user_location',
					'user_website',
					'read_stream',
					'read_mailbox',
					'read_page_mailboxes',
					'read_insights',
					'publish_stream',
					'publish_pages',
					'manage_pages',
					'offline_access'
					*/
					'public_profile',
					'user_friends',
					'email',
					'read_mailbox',
					'read_page_mailboxes',
					'manage_pages',
					'publish_actions',
					'publish_pages',
					'read_insights'
				));
				Url::redirect($loginUrl);
				return null;
			}
			else
			{
				$session = null;
				try
				{
					$session = $helper->getSessionFromRedirect();
				}
				catch (FacebookRequestException $ex)
				{
					// When Facebook returns an error
				}
				catch (\Exception $ex)
				{
					// When validation fails or other local issues
				}

				if ($session)
				{
					// Logged in
				}
				return $session;
			}
		}

		protected function _api($method, $path, $params=null, $obj='Facebook\GraphObject')
		{
			try
			{
				return (new FacebookRequest(
					$this->_session, $method, $path, $params
				))->execute()->getGraphObject($obj);
			}
			catch (FacebookPermissionException $ex)
			{
				// When Facebook returns an permission error
				throw $ex;
			}
			catch (FacebookRequestException $e)
			{
				// The Graph API returned an error
				var_dump($e);
			}
			catch (\Exception $e)
			{
				// Some other error occurred
				var_dump($e);
			}
			return null;
		}

		/**
		 * @return \Facebook\GraphUser|null
		 */
		public function getProfile()
		{
			return $this->_api('GET', '/me', null, GraphUser::className());
		}

		/*
		 * @param string $id
		 * @return \Facebook\GraphObject|null
		 */
		public function getFriends($id)
		{
			return $this->_api('GET', "/$id/friends");
		}

		/**
		 * @param string $id
		 * @return \Facebook\GraphObject|null
		 */
		public function getAccounts($id)
		{
			return $this->_api('GET', "/$id/accounts");
		}

		/**
		 * @param string $id
		 * @return \Facebook\GraphObject|null
		 */
		public function getObject($id)
		{
			return $this->_api('GET', "/$id");
		}

		/**
		 * @param string $id
		 * @param string $type
		 * @return \Facebook\GraphObject|null
		 */
		public function getPagePicture($id, $type='normal')
		{
			return $this->_api('GET', "/$id/picture", array('redirect' => false, 'type' => $type));
		}

		/**
		 * @param string $id
		 * @return \Facebook\GraphObject|null
		 */
		public function getPageDetails($id)
		{
			$obj = $this->getObject($id)->asArray();
			$obj['picture'] = $this->getPagePicture($id)->asArray();
			return new GraphObject($obj);
		}

		/*
		 * @return \Facebook\GraphObject|null
		 */
		public function getPageFeed($id, $extra=array())
		{
			return $this->_api('GET', "/$id/feed", $extra);
		}

		/*
		 * @return \Facebook\GraphObject|null
		 */
		public function getPagePosts($id, $extra=array())
		{
			return $this->_api('GET', "/$id/posts", $extra);
		}

		/*
		 * @return \Facebook\GraphObject|null
		 */
		public function getPageConversations($id, $extra=array())
		{
			return $this->_api('GET', "/$id/conversations", $extra);
		}

		/*
		 * @return \Facebook\GraphObject|null
		 */
		public function getLikes($id)
		{
			return $this->_api('GET', "/$id/likes");
		}

		/*
		 * @return \Facebook\GraphObject|null
		 */
		public function getComments($id, $extra=array())
		{
			return $this->_api('GET', "/$id/comments", array_merge(array('summary' => true, 'fields'=>'id,message,from,created_time,attachment,like_count,user_likes,parent,comment_count', 'filter' => 'stream'), $extra));
		}

		/*
		 * @return \Facebook\GraphObject|null
		 */
		public function getMessages($id)
		{
			return $this->_api('GET', "/$id/messages");
		}

		/*
		 * @return \Facebook\GraphObject|null
		 */
		public function postLike($id)
		{
			//var_dump($this->_session->getAccessToken(), "/$id/likes");
			return $this->_api('POST', "/$id/likes");
		}

		/*
		 * @return \Facebook\GraphObject|null
		 */
		public function deleteLike($id)
		{
			//var_dump($this->_session->getAccessToken(), "/$id/likes");
			return $this->_api('DELETE', "/$id/likes");
		}

		/*
		 * @return \Facebook\GraphObject|null
		 */
		public function postComment($id, $message)
		{
			return $this->_api('POST', "/$id/comments", array('message' => $message));
		}

		/*
		 * @return \Facebook\GraphObject|null
		 */
		public function postMessage($id, $message)
		{
			return $this->_api('POST', "/$id/messages", array('message' => $message));
		}

		/*
		 * @return \Facebook\GraphObject|null
		 */
		public function getInsight($id, $metric, $period)
		{
			return $this->_api('GET', "/$id/insights/$metric", array('period' => $period));
		}
	}
}