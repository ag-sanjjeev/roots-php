<?php

/**
 * ROOTS PHP MVC FRAMEWORK
 *
 * @category Framework
 * @author ag-sanjjeev 
 * @copyright 2025 ag-sanjjeev
 * @license https://github.com/ag-sanjjeev/roots-php/LICENSE MIT
 * @version Release: @1.0.0@
 * @link https://github.com/ag-sanjjeev/roots-php
 * @since This is available since Release 1.0.0
 */

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
 * Which has registers handlers for errors and exception and Timezone.
 * It has instance, run and showValue methods
 *
 */
class Main
{

	/**
   * Static property this class instance.
   *
   * @var Main
   */
	public static Main $instance;

	/**
	 * Static property root path
   *
   * @var string
   */
	public static string $rootPath;

	/**
	 * Static property environment
   *
   * @var string
   */
	public static string $environment;

	/**
	 * Static property timezone
   *
   * @var string
   */
	public static string $timezone;

	/**
   * Constructs a new Main object.
   */
	public function __construct()
	{		
		self::$instance = $this; // setting this class instance
		$this->route = new Route; // creating Route class object

		// Setting configurations
		self::$rootPath = Configuration::get('application.root_path');
		self::$environment = Configuration::get('application.environment');
		self::$timezone = Configuration::get('application.timezone');
		
		// Register Exception and Error Handler
		ExceptionHandler::register(self::$environment);

		// Set Default Timezone
		if (!is_null(self::$timezone)) {
			date_default_timezone_set(self::$timezone);
		}

		// Throws an exception and stops execution when root path is not defined in configuration
		if (is_null(self::$rootPath)) {
			throw new Exception("Application Root Path Missing Error", 1);
			die();
		}
	}

	/**
   * Gets instance of this class.
   *
   * @return object self::$instance.
   */
	public static function instance(): object
	{
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
   * Gather requests and Delivers response.
   */
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

	/**
   * Formats the given value.
   *
   * @param mixed $value.
   */
	public function showValue(mixed $value=''): void
	{
		echo "<pre>";
		print_r($value);		
		echo "</pre>";
	}
}