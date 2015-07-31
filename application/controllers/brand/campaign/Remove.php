<?php
namespace app\controllers\brand\campaign
{
	use app\helpers\Alert;
	use app\helpers\Json;
	use app\helpers\MongoDoc;
	use app\helpers\Url;
	use app\helpers\UserSession;
	use app\libraries\FormValidator;
	use app\models\Brand;
	use app\models\Campaign;
	use app\models\notify\NotifyBrandCampaign;

	class Remove extends _Main
	{
		public function post()
		{
			try {
				$campaign = new Campaign(null);
				$cinfo = $campaign->find_modify_one(array(
					'_id' => new \MongoId($this->input->post('id')),
					'brand' => UserSession::get('user._id'),
					'state' => 'pending'
				), array(
					'$set' => array(
						'state' => 'rejected'
					)
				));
				if ( ! $cinfo)
				{
					$this->_403();
				}

				Json::success('Campaign removed');
			} catch (\Exception $e) {
				Json::error($e->getMessage());
			}
		}

		protected function _get_data($binfo)
		{
			$data = array(
				'brand' => $binfo['_id'],
				'points' => 0,
				'state' => 'pending'
			);

			if ( ! in_array($data['type'] = $this->input->post('type'), array('digital-pr', 'ad-serving', 'custom')))
			{
				throw new \Exception('Invalid type: '.$data['type']);
			}

			if ($data['type'] == 'digital-pr')
			{
				if ( ! in_array($data['subtype'] = $this->input->post('subtype'), array('create', 'amplify')))
				{
					throw new \Exception('Invalid subtype: '.$data['type']);
				}

				$data['social'] = array();
				$key = 'social_'.$data['subtype'];
				foreach (array(
							'facebook',
							'twitter',
							'instagram',
							'google-analytics',
							'google-youtube',
							'google-plus',
							'vine'
					) as $k)
				{
					$data['social'][$k] = $this->input->post($key, str_replace('-', '_', $k));

					if ($data['subtype'] == 'create')
					{
						$data['social'][$k] = $data['social'][$k]? true: false;
					}
				}
			}

			$df = $this->input->post('date', 'from');
			$dt = $this->input->post('date', 'to');
			if ( ! ($df && $dt))
			{
				throw new \Exception('Dates must be specified');
			}

			$df_ts = (new \DateTime($df, new \DateTimeZone(UserSession::get('user.timezone')?:TIMEZONE)))->setTimezone(new \DateTimeZone(TIMEZONE))->getTimestamp();
			$dt_ts = (new \DateTime($dt, new \DateTimeZone(UserSession::get('user.timezone')?:TIMEZONE)))->setTimezone(new \DateTimeZone(TIMEZONE))->getTimestamp();
			$now = strtotime(date('Y-m-d'));

			if (($df_ts - $now) < 0)
			{
				throw new \Exception('Start date must not be in past');
			}
			if ($df_ts > $dt_ts)
			{
				throw new \Exception('End date must be greater than start date');
			}

			$data['date'] = array(
				'start' => array(
					'date' => $df,
					'timestamp' => $df_ts
				),
				'end' => array(
					'date' => $dt,
					'timestamp' => $dt_ts
				)
			);

			if ( ! ($data['title'] = $this->input->post('title')))
			{
				throw new \Exception('Title must be specified');
			}
			$data['title'] = trim($data['title']);

			if (strlen($data['title']) < 10)
			{
				throw new \Exception('Title must be atleast 10 characters long');
			}

			if ( ! ($data['brief'] = $this->input->post('brief')))
			{
				throw new \Exception('Brief must be specified');
			}
			$data['brief'] = trim($data['brief']);

			if (strlen($data['brief']) < 50)
			{
				throw new \Exception('Brief must be atleast 50 characters long');
			}

			$data['influencers_select'] = array();
			if (($idx = $this->input->post('influencer_list')) !== null)
			{
				$data['influencers_select'] = MongoDoc::get($binfo, 'lists.'.$idx.'.influencers', array());
			}

			return $data;
		}
	}
}