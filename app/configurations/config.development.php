<?php

/**
* This is a configuration file for development environment
*/

return [
	'application' => [
		'root_path' => dirname(dirname(__DIR__)),
		'debug' => true,
		'error_reporting' => E_ALL
	],
	'database' => [
		'driver' => 'mysql',
		'host' => 'localhost',
		'port' => 3306,
		'dbname' => 'roots_db',
		'username' => 'root',
		'password' => ''
	],
	'response' => [
		404 => dirname(dirname(__DIR__)) . '/public/responses/404.view.php',
		500 => dirname(dirname(__DIR__)) . '/public/responses/500.view.php'
	]
];