<?php
namespace app\controllers\influencer\campaign
{
	use app\helpers\Alert;
	use app\helpers\MongoDoc;
	use app\helpers\Url;
	use app\helpers\UserSession;
	use app\libraries\FormValidator;
	use app\models\Brand;
	use app\models\Campaign;
	use app\models\notify\NotifyBrandCampaign;

	class Comment extends _Main
	{
		public function post($channel)
		{
			switch ($channel)
			{
				case 'admin':
					$this->_channel_admin();
					break;

				case 'brand':
					$this->_channel_brand();
					break;
			}
		}

		protected function _channel_admin()
		{
			try {
				$id = $this->input->post('id');
				$campaign = new Campaign($id);
				$cinfo = $campaign->get();
				if ( ! $cinfo)
				{
					throw new \Exception('Invalid campaign');
				}

				$comment = $this->input->post('comment');
				if (empty($comment))
				{
					throw new \Exception('Comment must not be empty');
				}

				$uid = UserSession::get('user._id');
				$influencer = (string)$uid;
				$comments = MongoDoc::get($cinfo, 'comments.admin_influencer', array());
				if ( ! isset($comments[$influencer]))
				{
					$comments[$influencer] = array();
				}

				$comments[$influencer][] = array(
					'from' => $uid,
					'from_username' => UserSession::get('user.username'),
					'user' => 'influencer',
					'created_at' => time(),
					'text' => $comment
				);
				$comments[$influencer] = array_slice($comments[$influencer] , -20);

				$campaign->update(array(
					'comments.admin_influencer' => $comments
				));

				Alert::once('success', 'Comment added', Url::referrer());
			} catch (\Exception $e) {
				Alert::once('error', $e->getMessage(), Url::referrer());
			}
		}

		protected function _channel_brand()
		{
			try {
				$id = $this->input->post('id');
				$campaign = new Campaign($id);
				$cinfo = $campaign->get();
				if ( ! $cinfo)
				{
					throw new \Exception('Invalid campaign');
				}

				$comment = $this->input->post('comment');
				if (empty($comment))
				{
					throw new \Exception('Comment must not be empty');
				}

				$uid = UserSession::get('user._id');
				$influencer = (string)$uid;
				$comments = MongoDoc::get($cinfo, 'comments.brand_influencer', array());
				if ( ! isset($comments[$influencer]))
				{
					$comments[$influencer] = array();
				}

				$comments[$influencer][] = array(
					'from' => $uid,
					'from_username' => UserSession::get('user.username'),
					'user' => 'influencer',
					'created_at' => time(),
					'text' => $comment
				);
				$comments[$influencer] = array_slice($comments[$influencer] , -20);

				$campaign->update(array(
					'comments.brand_influencer' => $comments
				));

				Alert::once('success', 'Comment added', Url::referrer());
			} catch (\Exception $e) {
				Alert::once('error', $e->getMessage(), Url::referrer());
			}
		}
	}
}