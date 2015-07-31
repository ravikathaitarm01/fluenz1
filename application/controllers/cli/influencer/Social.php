<?php
namespace app\controllers\cli\influencer
{
	use app\helpers\MongoDoc;
	use app\models\Influencer;
	use app\libraries\facebook as fb;
	use app\libraries\twitter as tw;
	use app\libraries\instagram as ig;
	use app\libraries\google as gl;
	use app\libraries\vine as vn;
	use app\libraries\klout as kl;
	use app\models\notify\NotifyInfluencerSocial;
	use app\controllers\cli\base\Social as BaseSocial;

	class Social extends BaseSocial
	{
		protected $_notify_social = null;

		public function __construct()
		{
			parent::__construct();
			$this->_notify_social = new NotifyInfluencerSocial();
		}

		public function index()
		{
			$fp = fopen('/tmp/fluenz.cli.influencer.social.lock', 'w');
			if(flock($fp, LOCK_EX | LOCK_NB))
			{
				//$this->walk_users();
				$this->walk_user('5569dcedfd5efa5d7b8b4574');
			}
			fclose($fp);
		}

		protected function _notify_invalidate($uid, $social)
		{
			$this->_notify_social->invalidate($uid, $social);
		}

		public function walk_user($id)
		{
			$user = new Influencer($id);
			$u  = $user->get();
			$this->_walk_social($u);
			$this->_insert_statistics($u);
		}

		public function walk_users()
		{
			$user = new Influencer(null);
			foreach ($user->filter(array()) as $u)
			{
				$this->_walk_social($u);
				$this->_insert_statistics($u);
			}
		}

		protected function _walk_social($u)
		{
			$user = new Influencer(null);
			$update = array();

			printf("%-15s %-30s\n", 'BEGIN', $u['_id']);
			$this->_compute_social($u, $update);
			if ($update)
			{
				$this->_compute_fluenz_score($u, $update);

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
						 'google-analytics',
						 'google-plus',
						 'vine',
						 'klout',
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
						case 'google-analytics':
							$this->_fetch_google_analytics($u, $a, $item);
							break;
						case 'google-plus':
							$this->_fetch_google_plus($u, $a, $item);
							break;
						case 'vine':
							$this->_fetch_vine($u, $a, $item);
							break;
						case 'klout':
							$this->_fetch_klout($u, $a, $item);
							break;
					}

					printf("%-15s %-30s\n", 'SET', $item);
					$update['social.'.$item] = $a;
				}
			}
		}

		protected function _compute_fluenz_score($u, &$update)
		{
			// Calculate Fluenz score
			$s = MongoDoc::get($update, 'social\\.google-analytics.ga_data.ga:sessions', 0);
			$p = 100;
			$map = array(
				1000 => 50,
				5000 => 60,
				10000 => 70,
				50000 => 75,
				100000 => 80,
				500000 => 85,
				2000000 => 90,
				5000000 => 95,
			);
			foreach ($map as $k=>$v)
			{
				if ($s < $k)
				{
					$p = $v;
					break;
				}
			}
			$update['score'] = round(($p + MongoDoc::get($update, 'social\\.klout.score.score', 0)) / 2);
		}
	}
}