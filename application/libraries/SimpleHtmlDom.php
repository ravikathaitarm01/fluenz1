<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 29 Jul 2013
 * Project: maveric
 *
 */
namespace app\libraries
{
	use sys\core\Library;

	require_once (APPPATH.'thirdparty/simple_html_dom/simple_html_dom.php');

	class SimpleHtmlDom extends Library
	{
		public function parse($body)
		{
			return str_get_html($body);
		}
	}
}