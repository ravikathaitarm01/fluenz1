<?php

/*
|--------------------------------------------------------------------------
| Database Variables
|--------------------------------------------------------------------------
|
| 'host' = Set a hostname
| 'username' = Set to database username
|
*/

$config['database'] = array(
	'default_driver' => 'mongodb',
	'default_config' => 'mongodb_fluenz',

	'configurations' => array(
		'mysql_test' => array(
			'mysql_charset' => 'utf8',
			'host'		=> 'localhost',
			'database'	=> 'debug',
			'username'	=> 'nbaztec',
			'password'	=> '',
		),

		'mongodb_fluenz' => array(
			'dsn'		=> '',
			'hostname'	=> 'database.stackmediazo.com',
			'port'		=> 27017,
			'username'	=> 'root',
			'password'	=> 'mongopassw0rd',
			'database'	=> 'fluenz',
			'options'	=> array(
				'write_concern'	=> 1,
				'fsync'			=> false,
				'timeout'		=> 50000
			),
		)
	)
);
