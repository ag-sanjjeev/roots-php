<?php

// This is a global exception handler to handle exceptions and errors

namespace roots\app\core;

use roots\app\core\Logger;
use roots\app\core\Response;
use \ErrorException;
use \Exception;
use \Throwable;

/**
 * ExceptionHandler Class
 */
class ExceptionHandler
{

	// public static instance property
	public static ExceptionHandler $instance;

	// public static string environment property
	public static string $environment;

	function __construct(string $environment)
	{
		self::$instance = $this;
		self::$environment = $environment ?? null;
		if (is_null(self::$environment) || empty(self::$environment)) {
			Logger::logWarning('App Environment Empty');
			self::$environment = 'production';
		}
		self::setErrorReporting();
	}

	// public static instance method
	public static function instance(string $environment): object
	{
		if (!isset(self::$instance)) {
			self::$instance = new self($environment);
		}
		return self::$instance;
	}

	// public static handleException method
	public static function handleException(Throwable $e): void
	{
		if (self::$environment == 'production') {
			self::displayProductionError($e);
		} else {
			self::displayDevelopmentError($e);
		}
		Logger::logError($e);
		exit; // stop the script execution
	}

	// public static handleError method
	public static function handleError(int $errorNo, string $errorString, string $errorFile, int|string $errorLine): void
	{
		// Array of error types
		$errorTypes = [
      E_ERROR => 'Error',
      E_WARNING => 'Warning',
      E_NOTICE => 'Notice',
      E_PARSE => 'Parsing Error',
      E_CORE_ERROR => 'Core Error',
      E_CORE_WARNING => 'Core Warning',
      E_COMPILE_ERROR => 'Compile Error',
      E_COMPILE_WARNING => 'Compile Warning',
      E_USER_ERROR => 'User Error',
      E_USER_WARNING => 'User Warning',
      E_USER_NOTICE => 'User Notice',
      E_STRICT => 'Strict Notice',
      E_RECOVERABLE_ERROR => 'Recoverable Error'
    ];

    // Error Type
    $errorType = isset($errorTypes[$errorNo]) ? $errorTypes[$errorNo] : 'Unknown Error';

    // Handle error by global exception handler
    throw new ErrorException("PHP [$errorType]: [$errorString] in [$errorFile] on line [$errorLine]", 0, $errorNo);
	}

	// private static displayDevelopmentError method
	private static function displayDevelopmentError(Throwable $e): void
	{
		Response::errorResponse(500, $e);
	}

	// private static displayProductionError method
	private static function displayProductionError(Throwable $e): void
	{
		Response::errorResponse(500);
	}

	// private static setErrorReporting method
	private static function setErrorReporting(): void
	{
		if (isset(self::$environment) && self::$environment == 'development') {
			error_reporting(E_ALL & ~E_NOTICE); // error report all except notices 
		} else {
			error_reporting(E_ERROR | E_WARNING | E_PARSE);
		}
	}

	// public static register method
	public static function register(string $environment): void
	{
		self::$instance = self::instance($environment);

		// set both exception and error handler
		set_exception_handler([self::$instance, 'handleException']); 
		set_error_handler([self::$instance, 'handleError']);
	}
}