<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 29 Jul 2013
 * Project: maveric
 *
 */
namespace sys\libraries;
use sys\core\Library;
use sys\thirdparty\codeigniter\Email;

class Mail extends Library
{
	protected  $_ci_email = null;

	public function __construct()
	{
		parent::__construct();
		require_once (BASEPATH.'thirdparty/codeigniter/Email.php');
		$this->_init();
	}

	protected function _init()
	{
		$this->_ci_email = new Email($this->config->item('email'));
	}

	public function from($from, $name = '', $return_path = null)
	{
		$this->_ci_email->from($from, $name, $return_path);
		return $this;
	}

	public function reply_to($replyto, $name = '')
	{
		$this->_ci_email->reply_to($replyto, $name);
		return $this;
	}

	// --------------------------------------------------------------------

	public function to($to)
	{
		$this->_ci_email->to($to);
		return $this;
	}

	// --------------------------------------------------------------------

	public function cc($cc)
	{
		$this->_ci_email->cc($cc);
		return $this;
	}

	// --------------------------------------------------------------------

	public function bcc($bcc, $limit = '')
	{
		$this->_ci_email->bcc($bcc, $limit);
		return $this;
	}

	// --------------------------------------------------------------------

	public function subject($subject)
	{
		$this->_ci_email->subject($subject);
		return $this;
	}

	// --------------------------------------------------------------------

	public function message($body)
	{
		$this->_ci_email->message($body);
		return $this;
	}

	// --------------------------------------------------------------------

	public function attach($filename, $disposition = '', $newname = null, $mime = '')
	{
		$this->_ci_email->attach($filename, $disposition, $newname, $mime);
		return $this;
	}

	// --------------------------------------------------------------------

	public function set_header($header, $value)
	{
		$this->_ci_email->set_header($header, $value);
		return $this;
	}

	public function send($auto_clear = true)
	{
		return $this->_ci_email->send($auto_clear);
	}

	public function set_alt_message($str = '')
	{
		$this->_ci_email->set_alt_message($str);
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Set Mailtype
	 *
	 * @param	string
	 * @return	Email
	 */
	public function set_mailtype($type = 'text')
	{
		$this->_ci_email->set_mailtype($type);
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Set Wordwrap
	 *
	 * @param	bool
	 * @return	Email
	 */
	public function set_wordwrap($wordwrap = true)
	{
		$this->_ci_email->word_wrap($wordwrap);
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Set Protocol
	 *
	 * @param	string
	 * @return	Email
	 */
	public function set_protocol($protocol = 'mail')
	{
		$this->_ci_email->set_protocol($protocol);
		return $this;
	}
}