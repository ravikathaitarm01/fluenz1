<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 29 Jul 2013
 * Project: maveric
 *
 */
namespace app\models\notify
{
	use app\core\Model;
	use app\models\simple\User as SimpleUser;
	use app\libraries\Mail;
	use sys\libraries\Display;
	use app\helpers\MongoDoc;
	use app\helpers\Url;
	use app\helpers\Time;

	class Notify extends Model
	{
		const CLC_NOTIFICATION = 'notifications';
		const CLC_MAIL_QUEUE = 'mail_queue';

		protected $_from_email = 'notify@dev.fluenz.io';
		protected $_display = null;
		protected $_mail = null;
		protected $_user = null;
		protected $_prefix = '';

		public function __construct()
		{
			parent::__construct();
			$this->_display = new Display();
			$this->_display->attach('Url', new Url(), true);
			$this->_display->attach('Time', new Time(), true);
			$this->_display->attach('MongoDoc', new MongoDoc(), true);
			$this->_mail = new Mail();
			$this->_user = new SimpleUser(null);
		}
		public function install()
		{
			$this->_db->selectCollection(self::CLC_NOTIFICATION)->ensureIndex(array(
						'recipients' => 1
					));
			$this->_db->createCollection(self::CLC_MAIL_QUEUE);
		}

		public function all()
		{
			return $this->_db->selectCollection(self::CLC_NOTIFICATION)->find();
		}

		public function get($id, $mark_read=false, $user_id=null)
		{
			if ( ! ($doc = $this->_db->selectCollection(self::CLC_NOTIFICATION)->findOne(array(
				'_id' => $id
			))))
			{
				return null;
			}

			$doc['sender'] = $this->_user->filter(array(
				'_id' => $doc['sender']
			), array(
				'username' => true,
				'name' => true,
				'email' => true,
				'type' => true
			))->getNext();
			if ($mark_read)
			{
				$this->_db->selectCollection(self::CLC_NOTIFICATION)->update(array(
					'_id' => $doc['_id']
				), array(
					'$pull' => array(
						'readers' => $user_id
					)
				));
			}

			return $doc;
		}

		public function get_all($userid, $mark_read=false)
		{
			if ( ! ($d = $this->_db->selectCollection(self::CLC_NOTIFICATION)->find(array(
				'recipients' => $userid
			))))
			{
				return array();
			}

			$result = array();
			foreach ($d->sort(array('_id' => -1)) as $doc)
			{
				$doc['sender'] = $this->_user->filter(array(
					'_id' => $doc['sender']
				), array(
					'username' => true,
					'name' => true,
					'email' => true,
					'type' => true
				))->getNext();
				$result[] = $doc;
				if ($mark_read)
				{
					$this->_db->selectCollection(self::CLC_NOTIFICATION)->update(array(
						'_id' => $doc['_id']
					), array(
						'$pull' => array(
							'readers' => $userid
						)
					));
				}
			}

			return $result;
		}

		public function read($id, $userid)
		{
			$this->_db->selectCollection(self::CLC_NOTIFICATION)->update(array(
				'_id' => $id
			), array(
				'$pull' => array(
					'readers' => $userid
				)
			));
		}

		public function add($data)
		{
			$this->_raise_err($this->_db->selectCollection(self::CLC_NOTIFICATION)->insert(array(
				'sender' => $data['sender'],
				'recipients' => $data['recipients'],
				'readers' => $data['recipients'],
				'text' => $data['text'],
				'type' => $data['type'],
				'url' => $data['url']
			)));
		}

		protected function _mail_body($template, $data)
		{
			return $this->_display->view(array('mail/header.php', 'mail/'.$template, 'mail/footer.php'), $data, true, false);
		}

		protected function _body($template, $data)
		{
			return $this->_display->view(array('notify/'.$template), $data, true, false);
		}

		protected function _mail($to, $subject, $message, $from)
		{
			return $this->_mail->to($to)
				->from($from)
				->subject($subject)
				->message($message)
				->send();
		}

		public function mail_enqueue($data)
		{
			$this->_raise_err($this->_db->selectCollection(self::CLC_MAIL_QUEUE)->insert(array(
				'to' => is_array($data['to'])? $data['to']: array($data['to']),
				'reply_to' => isset($data['reply_to'])? $data['reply_to']:null,
				'from' => $data['from'],
				'subject' => $data['subject'],
				'message' => $data['message'],
				'processing' => false,
				'tries' => 0,
				'sent' => false
			)));
		}

		public function mail_dequeue($count=1)
		{
			$docs = $this->_db->selectCollection(self::CLC_MAIL_QUEUE)->find(array(
				'sent' => false,
				'processing' => false
			))->sort(array(
				'_id' => 1
			))->limit($count);

			$mails = array();
			foreach ($docs as $doc)
			{
				$mails[] = $doc;
				$this->_raise_err($this->_db->selectCollection(self::CLC_MAIL_QUEUE)->update(array(
					'_id' => $doc['_id']
				), array(
					'$set' => array(
						'processing' => true
				))));
			}
			return $mails;
		}

		public function mail_update($id, $update)
		{
			$this->_raise_err($this->_db->selectCollection(self::CLC_MAIL_QUEUE)->update(array(
				'_id' => $id
			), array(
				'$set' => $update)));
		}

		public function mail_get_all()
		{
			return $this->_db->selectCollection(self::CLC_MAIL_QUEUE)->find(array(
				'processing' => false
			));
		}

		public function mail_remove($id)
		{
			$this->_raise_err($this->_db->selectCollection(self::CLC_MAIL_QUEUE)->remove(array(
				'_id' => $id
			)));
		}

		public function mail_purge($find, $options=array('multiple'=>true))
		{
			return $this->_db->selectCollection(self::CLC_MAIL_QUEUE)->remove($find, $options);
		}

		public function filter($find, $fields=array())
		{
			return $this->_db->selectCollection(self::CLC_NOTIFICATION)->find($find, $fields);
		}

		public function modify($find, $modify, $options=array('multiple'=>true))
		{
			return $this->_db->selectCollection(self::CLC_NOTIFICATION)->update($find, $modify, $options);
		}

		public function purge($find, $options=array('multiple'=>true))
		{
			return $this->_db->selectCollection(self::CLC_NOTIFICATION)->remove($find, $options);
		}
	}
}