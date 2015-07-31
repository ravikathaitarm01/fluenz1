<?php
namespace app\controllers\influencer
{
	use app\controllers\_Main as BaseMain;
	use app\helpers\UserSession;
	use app\helpers\MongoDoc;
	use app\models\Statistics;


	class _Main extends BaseMain
	{
		public function __construct($login=true)
		{
			parent::__construct(true);
			if (UserSession::get('user.type') !== 'influencer')
			{
				$this->_403();
			}
		}

		protected function _get_social_differential($info, $days=5)
		{
			$stats = new Statistics();
			$stat = $stats->get($info['_id'], date('Y-m-d', strtotime('-'.$days.' days')));

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

			$result = array();
			foreach ($items as $k => $fields)
			{
				if ( ! ($a=MongoDoc::get($info, 'social.'.$k)))
				{
					continue;
				}

				$c = array();
				if ($stat)
				{

					foreach ($fields as $f)
					{
						//echo 'VAL', PHP_EOL;
						//var_dump($f, 'social.'.$k.'.'.$f, MongoDoc::get($stat, 'social.'.$k.'.'.$f, null));
						//var_dump($f, MongoDoc::get($a, $f, null));
						$p = 1.0;
						if ($stat && ($v_old=MongoDoc::get($stat, 'social.'.$k.'.'.$f, null)) !== null && ($v_new = MongoDoc::get($a, $f, null)) !== null)
						{
							/*
							switch ($k.'.'.$f)
							{
								case 'twitter.details.statuses_count':
									$x = strtotime(MongoDoc::get($a, 'details.created_at'));
									$d = floor((time() - $x)/(3600*24));
									$v_new1 = $d? ($v_new / $d) : 0;
									$d -= $days;
									$v_old1 = $d? ($v_new / $d) : 0;
									$c['details.statuses_per_day'] = round(($v_new1 - $v_old1)*100/$v_new1, 4);
									break;
							}
							*/
							//echo 'Found value: ', $v_new, ' - ' , $v_old, PHP_EOL;
							if (($v_new + $v_old) == 0)		// If both 0
							{
								$p = 0;
							}
							else if ($v_new)
							{
								$p = round(($v_new - $v_old)*100/$v_new, 4);
							}
						}
						$c[$f] = $p;
					}


				}
				if ($c)
				{
					$result[$k] = $c;
				}
			}

			return MongoDoc::explode($result);
		}
	}
}