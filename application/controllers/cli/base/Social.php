<?php
namespace app\controllers\cli\base
{
	use app\core\Controller;
	use app\helpers\MongoDoc;
	use app\libraries\facebook as fb;
	use app\libraries\twitter as tw;
	use app\libraries\instagram as ig;
	use app\libraries\google as gl;
	use app\libraries\vine as vn;
	use app\libraries\klout as kl;
	use app\models\notify\NotifyInfluencerSocial;
	use app\models\notify\NotifyBrandSocial;
	use app\models\Statistics;

	abstract class Social extends Controller
	{
		public function __construct()
		{
			parent::__construct();
		}

		public function index()
		{
			throw new \Exception('Not Implemented');
		}

		public abstract function walk_user($id);
		public abstract function walk_users();
		protected abstract function _notify_invalidate($uid, $social);

		protected function _fetch_status_facebook($u, &$status, $info, $social)
		{
			try {
				$sdk = new fb\SDK($info['access_token']);
				$d = $sdk->getPagePosts($info['id'], array('limit' => 1))->asArray()['data'];
				if (count($d))
				{
					$status= json_decode(json_encode($d[0]), true);
				}

			} catch (fb\FacebookAccessTokenException $e) {
				$this->_notify_invalidate($u['_id'], $social);
			}
		}

		protected function _fetch_status_twitter($u, &$status, $info, $social)
		{
			try {
				$sdk = new tw\SDK($info['oauth_token'], $info['oauth_token_secret']);
				$tweets = $sdk->getUserTweets($info['details']['id_str'], array('count' => 1, 'include_rts' => false));
				if (count($tweets))
				{
					$status = $tweets[0];
				}
			} catch (tw\TwitterAccessTokenException $e) {
				$this->_notify_invalidate($u['_id'], $social);
			}
		}

		protected function _fetch_status_instagram($u, &$status, $info, $social)
		{
			try {
				$sdk = new ig\SDK();
				$sdk->setAccessToken($info['access_token']);
				$d = $sdk->getUserMedia($info['id'], array('count' => 1));
				if (count($d))
				{
					$status = $d[0];
				}
			} catch (ig\InstagramAccessTokenException $e) {
				$this->_notify_invalidate($u['_id'], $social);
			}
		}

		protected function _fetch_status_google_youtube($u, &$status, $info, $social)
		{
			try {
				$token = $info['refresh_token'];
				$sdk = new gl\SDK();
				$sdk->setRefreshToken($token);
				$youtube = $sdk->getService('youtube');
				$r = $youtube->channels->listChannels('contentDetails', array(
					'id' => $info['id'],
				))->getItems();

				if ( ! count($r))
				{
					throw new \Exception('Unable to fetch channel details');
				}

				/**
				 * @var $r \Google_Service_YouTube_ChannelListResponse
				 */
				$playlist = $r[0]['contentDetails']['relatedPlaylists']['uploads'];

				$r = $youtube->playlistItems->listPlaylistItems('snippet', array(
					'playlistId' => $playlist,
					'maxResults' => 1
				));
				$status = json_decode(json_encode($r['items'][0]->toSimpleObject()->snippet), true);
			} catch (\Google_Auth_Exception $e) {
				$this->_notify_invalidate($u['_id'], $social);
			}
		}

		protected function _fetch_status_google_plus($u, &$status, $info, $social)
		{
			try {
				$token = $info['refresh_token'];
				$sdk = new gl\SDK();
				$sdk->setRefreshToken($token);
				$plus = $sdk->getService('plus');
				$r = $plus->activities->listActivities('me', 'public', array('maxResults' => 1));
				$status = json_decode(json_encode($r['items'][0]->toSimpleObject()), true);
			} catch (\Google_Service_Exception $e) {
				$this->_notify_invalidate($u['_id'], $social);
			}
		}

		protected function _fetch_facebook($u, &$info, $social)
		{
			try {
				$sdk = new fb\SDK($info['access_token']);
				$details = $sdk->getPageDetails($info['id'])->asArray();
				$insight = json_decode(json_encode($sdk->getInsight($info['id'], 'page_engaged_users', 'week')->asArray()), true);
				$t = MongoDoc::get($insight, 'data.0.values', array(array('value' => 0)));
				$t = $t[count($t)-1]['value'];
				$info['insights'] = array(
					'engaged_users' => $t
				);
				$info['details'] = $details;
			} catch (fb\FacebookAccessTokenException $e) {
				$this->_notify_invalidate($u['_id'], $social);
			}
		}

		protected function _fetch_twitter($u, &$info, $social)
		{
			try {
				$sdk = new tw\SDK($info['oauth_token'], $info['oauth_token_secret']);
				$info['details'] = $sdk->getProfile();
			} catch (tw\TwitterAccessTokenException $e) {
				$this->_notify_invalidate($u['_id'], $social);
			}
		}

		protected function _fetch_instagram($u, &$info, $social)
		{
			try {
				$sdk = new ig\SDK();
				$token = $info['access_token'];
				$sdk->setAccessToken($token);
				$info = $sdk->getUser($info['id']);
				$info['access_token'] = $token;
			} catch (ig\InstagramAccessTokenException $e) {
				$this->_notify_invalidate($u['_id'], $social);
			}
		}

		protected function _fetch_google_youtube($u, &$info, $social)
		{
			try {
				$token = $info['refresh_token'];
				$sdk = new gl\SDK();
				$sdk->setRefreshToken($token);
				$youtube = $sdk->getService('youtube');
				$r = $youtube->channels->listChannels('snippet,statistics', array(
					'id' => $info['id'],
				))->getItems();

				if ( ! count($r))
				{
					throw new \Exception('Unable to fetch channel details');
				}

				/**
				 * @var $r \Google_Service_YouTube_ChannelListResponse
				 */
				$info = (array)$r[0]->toSimpleObject();
				$info['refresh_token'] = $token;
			} catch (\Google_Auth_Exception $e) {
				$this->_notify_invalidate($u['_id'], $social);
			}
		}

		protected function _fetch_google_analytics($u, &$info, $social)
		{
			try {
				$token = $info['refresh_token'];
				$sdk = new gl\SDK();
				$sdk->setRefreshToken($token);
				$analytics = $sdk->getService('analytics');

				$result = (array)$analytics->management_profiles->get($info['accountId'], $info['webPropertyId'], $info['id'])->toSimpleObject();
				$d = (array)$analytics->data_ga->get('ga:'.$info['id'], '30daysAgo', 'yesterday', 'ga:sessions,ga:avgSessionDuration')->toSimpleObject();
				$result['ga_data'] = $d['totalsForAllResults'];
				$info = $result;
				$info['refresh_token'] = $token;
			} catch (\Google_Auth_Exception $e) {
				var_dump($e);
				$this->_notify_invalidate($u['_id'], $social);
			}
		}

		protected function _fetch_google_plus($u, &$info, $social)
		{
			try {
				$token = $info['refresh_token'];
				$sdk = new gl\SDK();
				$sdk->setRefreshToken($token);
				$plus = $sdk->getService('plus');
				$info = (array)$plus->people->get('me')->toSimpleObject();
				$info['refresh_token'] = $token;
			} catch (\Google_Service_Exception $e) {
				$this->_notify_invalidate($u['_id'], $social);
			}
		}

		protected function _fetch_vine($u, &$info, $social)
		{
			try {
				$creds = $info['credentials'];
				$sdk = new vn\SDK($creds['username'], $creds['password']);
				$info = $sdk->getUserMe();
				$info['credentials'] = $creds;
			} catch (\Exception $e) {
				$this->_notify_invalidate($u['_id'], $social);
			}
		}

		protected function _fetch_klout($u, &$info, $social)
		{
			try {
				$sdk = new kl\SDK();
				$info = $sdk->getUser($info['kloutId']);
			} catch (\Exception $e) {
				$this->_notify_invalidate($u['_id'], $social);
			}
		}

		protected function _insert_statistics($u)
		{
			$stats = new Statistics();
			$items = array(
				'facebook' => array(
					'details.likes',
					'insights.engaged_users'
				),
				'twitter' => array(
					'details.statuses_count',
					'details.followers_count',
				),
				'instagram' => array(
					'counts.followed_by',
				),
				'google-youtube' => array(
					'statistics.subscriberCount',
					'statistics.videoCount',
				),
				'google-analytics' => array(
					'ga_data.ga:sessions',
				),
				'vine' => array(
					'followerCount',
				),
				'klout' => array(
					'score.score',
				),
			);

			$data = array();
			foreach ($items as $k => $fields)
			{
				if ( ! ($a=MongoDoc::get($u, 'social.'.$k)))
				{
					continue;
				}

				$c = array();
				foreach ($fields as $f)
				{
					$c[$f] = MongoDoc::get($a, $f, 0);
				}

				if ($c)
				{
					$data[$k] = $c;
				}
			}

			if ($data)
			{
				try {
					$stats->save($u['_id'], date('Y-m-d'), array(
						'social' => MongoDoc::explode($data)
					));
				} catch (\Exception $e) {
					echo 'Error updating document: ', $e->getMessage(), PHP_EOL;
				}
			}
		}
	}
}