<?php
namespace app\controllers\admin\campaign
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
				case 'brand':
					$this->_channel_brand();
					break;

				case 'influencer':
					$this->_channel_influencer();
					break;
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

				$comments = MongoDoc::get($cinfo, 'comments.admin_brand', array());
				$comments[] = array(
					'from' => UserSession::get('user._id'),
					'from_username' => UserSession::get('user.username'),
					'user' => 'admin',
					'created_at' => time(),
					'text' => $comment
				);
				$comments = array_slice($comments, -20);

				$campaign->update(array(
					'comments.admin_brand' => $comments
				));

				Alert::once('success', 'Comment added', Url::referrer());
			} catch (\Exception $e) {
				Alert::once('error', $e->getMessage(), Url::referrer());
			}
		}

		protected function _channel_influencer()
		{
			try {
				$id = $this->input->post('id');
				$campaign = new Campaign($id);
				$cinfo = $campaign->get();
				if ( ! $cinfo)
				{
					throw new \Exception('Invalid campaign');
				}

				if ( ! $influencer = $this->input->post('influencer'))
				{
					throw new \Exception('Invalid influencer');
				}

				$comment = $this->input->post('comment');
				if (empty($comment))
				{
					throw new \Exception('Comment must not be empty');
				}

				$comments = MongoDoc::get($cinfo, 'comments.admin_influencer', array());
				if ( ! isset($comments[$influencer]))
				{
					$comments[$influencer] = array();
				}

				$comments[$influencer][] = array(
					'from' => UserSession::get('user._id'),
					'from_username' => UserSession::get('user.username'),
					'user' => 'admin',
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
	}
}