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
$config['site'] = array(
	'base_url'		=> '',
	'asset_path_url' => '/fzdev/assets',
	'default_controller' => 'Index',
	'assets' => array(
		'less' => array(
/*
			array(
				'input' => ROOTPATH.'assets/frontend/less/main.less',
				'output' => ROOTPATH.'assets/frontend/css/main.css',
			),*/
			array(
				'input' => ROOTPATH.'assets/main/less/main.less',
				'output' => ROOTPATH.'assets/main/css/main.css',
			)
		)
	)
);
