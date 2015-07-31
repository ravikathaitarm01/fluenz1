<?php
namespace app\controllers\cli\brand
{
	use app\helpers\MongoDoc;
	use app\models\Brand;
	use app\libraries\facebook as fb;
	use app\libraries\twitter as tw;
	use app\libraries\instagram as ig;
	use app\libraries\google as gl;
	use app\libraries\vine as vn;
	use app\libraries\klout as kl;
	use app\models\notify\NotifyBrandSocial;
	use app\controllers\cli\base\Social as BaseSocial;

	class Social extends BaseSocial
	{
		protected $_notify_social = null;

		public function __construct()
		{
			parent::__construct();
			$this->_notify_social = new NotifyBrandSocial();
		}

		public function index()
		{
			$fp = fopen('/tmp/fluenz.cli.brand.social.lock', 'w');
			if(flock($fp, LOCK_EX | LOCK_NB))
			{
				//$this->walk_users();
				$this->walk_user('5569d31ffd5efa1b6c8b456f');
			}
			fclose($fp);
		}

		protected function _notify_invalidate($uid, $social)
		{
			$this->_notify_social->invalidate($uid, $social);
		}

		public function walk_user($id)
		{
			$user = new Brand($id);
			$u  = $user->get();
			$this->_walk_social($u);
			$this->_insert_statistics($u);
		}

		public function walk_users()
		{
			$user = new Brand(null);
			foreach ($user->filter(array()) as $u)
			{
				$this->_walk_social($u);
				$this->_insert_statistics($u);
			}
		}

		protected function _walk_social($u)
		{
			$user = new Brand(null);
			$update = array();

			printf("%-15s %-30s\n", 'BEGIN', $u['_id']);
			$this->_compute_social($u, $update);
			if ($update)
			{
				$this->_compute_status_latest($u, $update);

				printf("%-15s %-30s\n", 'UPDATE', $u['_id']);

				$user->modify(array(
					'_id' => $u['_id']
				), array(
					'$set' => $update
				));
			}
			printf("%-15s %-30s\n", 'END', $u['_id']);
		}

		protected function _compute_social($u, &$update)
		{
			$invalidated = MongoDoc::get($u, 'social_invalidated', array());

			foreach (array(
						 'facebook',
						 'twitter',
						 'instagram',
						 'google-youtube',
						 'google-plus',
					 ) as $item)
			{
				if ($a = MongoDoc::get($u, 'social.'.$item))
				{
					if (in_array($item, $invalidated))
					{
						printf("%-15s %-30s\n", 'SKIP', $item);
						continue;
					}

					switch ($item)
					{
						case 'facebook':
							$this->_fetch_facebook($u, $a, $item);
							break;
						case 'twitter':
							$this->_fetch_twitter($u, $a, $item);
							break;
						case 'instagram':
							$this->_fetch_instagram($u, $a, $item);
							break;
						case 'google-youtube':
							$this->_fetch_google_youtube($u, $a, $item);
							break;
						case 'google-plus':
							$this->_fetch_google_plus($u, $a, $item);
							break;
					}

					printf("%-15s %-30s\n", 'SET', $item);
					$update['social.'.$item] = $a;
				}
			}
		}

		protected function _compute_status_latest($u, &$update)
		{
			$river = array();
			if (MongoDoc::get($u, 'social_river.enabled', false))
			{
				foreach (array(
							 'facebook',
							 'twitter',
							 'instagram',
							 'google-youtube',
							 'google-plus',
						 ) as $item)
				{
					if ($info = MongoDoc::get($update, 'social\\.'.$item))
					{
						$s = null;
						switch ($item)
						{
							case 'facebook':
								$this->_fetch_status_facebook($u, $s, $info, $item);
								break;
							case 'twitter':
								$this->_fetch_status_twitter($u, $s, $info, $item);
								break;
							case 'instagram':
								$this->_fetch_status_instagram($u, $s, $info, $item);
								break;
							case 'google-youtube':
								$this->_fetch_status_google_youtube($u, $s, $info, $item);
								break;
							case 'google-plus':
								$this->_fetch_status_google_plus($u, $s, $info, $item);
								break;
						}


						if ($s)
						{
							printf("%-15s %-30s\n", 'STATUS-SET', $item);
							$river[$item] = $s;
						}
					}
				}
				$update['social_river.data'] = array_replace_recursive(MongoDoc::get($u, 'social_river.data', array()), $river);
			}
		}
	}
}