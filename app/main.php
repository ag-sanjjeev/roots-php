<?php

namespace roots\app;

use roots\app\core\Configurations;

/**
 * Class Main
 *
 * This is a main class file. Which has control and co-ordinate 
 * features of the Model View Control architecture.
 *
 * @copyright 2024 ag-sanjjeev
 * @license https://github.com/ag-sanjjeev/roots-php/LICENSE MIT
 * @version Release: @1.0@
 * @link https://github.com/ag-sanjjeev/roots-php
 * @since Class available since Release 1.0
 */

class Main
{
	// Main instance static property
	public static Main $main;

	// Configurations instance static property
	public Configurations $config;

	// static class instance property need to create like private static Configuration $configuration;
	public function __construct()
	{		
		self::$main = $this;
		
		$this->config = Configurations::getInstance();
	}

	public function run(): void
	{
		// require $this->config->get('response.404');
		echo "application running";
	}

	public function showValue(mixed $value=''): mixed
	{
		echo "<pre>";
		print_r($value);
		echo "</pre>";
	}
}