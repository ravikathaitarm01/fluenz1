<?php
namespace app\controllers\cli\mail
{
	use app\core\Controller;
	use app\libraries\Mail;
	use app\models\notify\Notify;

	class Send extends Controller
	{
		const MAX_TRIES =  3;

		public function index()
		{
			$this->_dispatch(50);
			$this->_cleanup();
		}

		protected function _dispatch($chunk=50)
		{
			$mail = new Mail();
			$notify = new Notify();
			foreach ($notify->mail_dequeue($chunk) as $doc)
			{
				if ($doc['tries'] == self::MAX_TRIES)
				{
					continue;
				}
				try {
					if ($mail->to($doc['to'])
						->from($doc['from'])
						->reply_to(isset($doc['reply_to'])? $doc['reply_to']: $doc['from'])
						->subject($doc['subject'])
						->message($doc['message'])
						->send())
					{
						$notify->mail_update($doc['_id'], array(
							'processing' => false,
							'sent' => true
						));
					}
					else
					{
						$notify->mail_update($doc['_id'], array(
							'processing' => false,
							'tries' => ($doc['tries'] + 1)
						));
					}
				} catch (\Exception $e) {
					echo $e->getTraceAsString().PHP_EOL;
				}
			}
		}

		protected function _cleanup()
		{
			$notify = new Notify();
			$notify->mail_purge(array(
				'$or' => array(
					array('sent' => true),
					array('tries' => self::MAX_TRIES)
				)
			));
		}

	}
}
