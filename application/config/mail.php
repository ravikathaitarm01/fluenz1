<?php

/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your application root. Typically this will be your base URL,
| WITH a trailing slash:
|
|	http://example.com/
|
|
*/
$config['email'] = array(
	'protocol' => 'smtp',
	'smtp_host' => 'smtp.mandrillapp.com',
	'smtp_user' => 'honey@armdigital.in',
	'smtp_pass' => 'gEWZiop8NLaqwTSXhhdQeQ',
	'smtp_port' => 587,
	'smtp_timeout' => 240,
	'smtp_keepalive' => true,
	'charset' => 'iso-8859-1',
	'mailtype' => 'html',
	'useragent' => '',
	'wordwrap' => false,
	'crlf' => "\r\n",
	'newline' => "\r\n",
);
