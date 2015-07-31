<?php
namespace app\controllers\cli\mail
{
	use app\core\Controller;

	class Receive extends Controller
	{
		public function index($type)
		{
			$data = var_export($this->input->post(), true).PHP_EOL;
			$data .= var_export($this->input->get(), true).PHP_EOL;
			$data .= var_export($this->input->server(), true).PHP_EOL;
			$data .= $type.PHP_EOL;
			file_put_contents('/tmp/sorm.mail.out', $data);
		}

	}
}