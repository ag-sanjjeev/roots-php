<?php

namespace roots\app;

use roots\app\core\Configuration;
use roots\app\core\Request;
use roots\app\core\Response;
use roots\app\core\Route;
use roots\app\core\ExceptionHandler;
use Exception;

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
	public static Main $instance;

	// Configurations instance property
	public Configuration $config;

	// Request instance property
	public Request $request;

	// Response instance property
	public Response $response;

	// Route instance property
	public Route $route;

	// root path static property
	public static string $rootPath;

	// environment static property
	public static string $environment;

	// timezone static property
	public static string $timezone;

	// static class instance property need to create like private static Configuration $configuration;
	public function __construct()
	{		
		self::$instance = $this;
		$this->route = new Route;
		self::$rootPath = Configuration::get('application.root_path');
		self::$environment = Configuration::get('application.environment');
		self::$timezone = Configuration::get('application.timezone');
		
		// Register Exception and Error Handler
		ExceptionHandler::register(self::$environment);

		// Set Default Timezone
		if (!is_null(self::$timezone)) {
			date_default_timezone_set(self::$timezone);
		}

		if (is_null(self::$rootPath)) {
			throw new Exception("Application Root Path Missing Error", 1);
			die();
		}
	}

	public static function instance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function run(): void
	{
		// Getting current request method and urlPath 
		$requestMethod = Request::method();
		$urlPath = Request::urlPath();

		// Get corresponding callback details
		$callbackDetails = Route::callbackDetails($requestMethod, $urlPath);
		$callback = $callbackDetails['callback'] ?? null;
		$middleware = $callbackDetails['middleware'] ?? null;
		$urlParameters = $callbackDetails['urlParameters'] ?? null;
		
		// deliver response for given callback details
		Response::deliver($callback, $middleware, $urlParameters);
	}

	public function showValue(mixed $value=''): void
	{
		echo "<pre>";
		print_r($value);		
		echo "</pre>";
	}
}