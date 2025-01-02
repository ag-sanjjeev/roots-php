<?php

namespace roots\app;

use roots\app\core\Configurations;
use roots\app\core\Request;

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

	// Request instance static property
	public Request $request;

	// static class instance property need to create like private static Configuration $configuration;
	public function __construct()
	{		
		self::$main = $this;
		
		$this->config = Configurations::getInstance();
		$this->request = new Request;
	}

	public function run(): void
	{
		// require $this->config->get('response.404');
		$this->request->a = '123';
		$this->showValue(Request::fullUrl());
		$this->showValue(Request::urlPath());
		$this->showValue(Request::method());
		$this->showValue(Request::urlParams('string'));
		$this->showValue($this->request->a);		
		$this->showValue(Request::inputsOnly(['p','q']));
		$this->showValue(Request::input('string'));
		$this->showValue(Request::inputsExcept('p'));
		$this->showValue(Request::hasInput('p'));
		$this->showValue(Request::missingInputs(['p', 'string']));
		$this->showValue(Request::acceptableContentType());
		$this->showValue(Request::isAcceptableContentType(['text/html', 'application/xhtml+xml']));
		$this->showValue(Request::ip());		

		echo "application running";
	}

	public function showValue(mixed $value=''): void
	{
		echo "<pre>";
		print_r($value);
		echo "</pre>";
	}
}