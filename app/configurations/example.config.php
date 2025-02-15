<?php

/**
 * ROOTS PHP MVC FRAMEWORK.
 *
 * This is template configurations for the project.
 * These configurations are stored as PHP array.
 *
 * @category Framework
 * @author ag-sanjjeev 
 * @copyright 2025 ag-sanjjeev
 * @license https://github.com/ag-sanjjeev/roots-php/LICENSE MIT
 * @version Release: @1.0.0@
 * @link https://github.com/ag-sanjjeev/roots-php
 * @since This is available since Release 1.0.0
 */

return [
	'application' => [
		'app_name' => 'application name',
		'session_name' => 'unpredictable-name',
		'root_path' => dirname(dirname(__DIR__)),
		'middleware_namespace' => 'roots\\app\\middlewares\\',
		'storage_directory' => 'storage',
		'timezone' => 'Asia/Kolkata',
		'environment' => 'development' // either development or production
	],
	'database' => [
		'driver' => 'mysql',
		'host' => 'localhost',
		'port' => 3306,
		'dbname' => 'database_name',
		'username' => 'db_user_name',
		'password' => 'secret_db_password'
	],
	'logger' => [
		'path' => 'app\logs',
		'max_files' => 5, // preserve old logs in each level
		'file_size' => 1048576 // 1 MB
	],
	'response' => [
		404 => dirname(dirname(__DIR__)) . '/public/responses/404.view.php',
		500 => dirname(dirname(__DIR__)) . '/public/responses/500.view.php'
	]
];