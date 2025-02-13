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

namespace roots\app\core;

use roots\app\core\Logger;
use roots\app\core\Response;
use \ErrorException;
use \Exception;
use \Throwable;

/**
 * Class ExceptionHandler
 *
 * Which has properties and methods to handle errors and exceptions.
 * It has instance, handleException, handleError, displayDevelopmentError, 
 * displayProductionError, setErrorReporting and register methods
 *
 */
class ExceptionHandler
{

	/**
	 * The singleton instance of the ExceptionHandler class.
	 *
	 * @var ExceptionHandler
	 */
	public static ExceptionHandler $instance;

	/**
	 * The current application environment (e.g., development, production).
	 *
	 * @var string
	 */
	public static string $environment;

	/**
	 * Constructor for the ExceptionHandler class.
	 *
	 * Initializes the exception handler and sets the application environment.
	 *
	 * @param string $environment The application environment (e.g., 'development', 'production').
	 */
	function __construct(string $environment)
	{
		// Set the singleton instance.
		self::$instance = $this;
		// Set the application environment.
		self::$environment = $environment ?? null;
		// Handle cases where the environment is not set.
		if (is_null(self::$environment) || empty(self::$environment)) {
			// Log a warning if the environment is empty.
			Logger::logWarning('App Environment Empty');
			// Set a default environment if none is provided.
			self::$environment = 'production';
		}
		// Configure error reporting based on the environment.
		self::setErrorReporting();
	}

	/**
	 * Returns the singleton instance of the ExceptionHandler.  
	 * Creates it if it doesn't exist.
	 *
	 * @param string $environment The application environment to use if a new instance is created.
	 * @return object The singleton ExceptionHandler instance.
	 */
	public static function instance(string $environment): object
	{
		// Check if an instance already exists.
		if (!isset(self::$instance)) {
			// Create a new instance if one doesn't exist.
			self::$instance = new self($environment);
		}
		// Return the singleton instance.
		return self::$instance;
	}

	/**
	 * Custom exception handler method to handles an uncaught exception.
	 *
	 * This method is responsible for logging the exception and 
	 * displaying an appropriate error message based on the application environment.
	 *
	 * @param Throwable $e The uncaught exception.
	 * @return void
	 */
	public static function handleException(Throwable $e): void
	{
		// Check the application environment.
		if (self::$environment == 'production') {
			// Display a generic error message in production.
			self::displayProductionError($e);
		} else {
			// Display a detailed error message in development.
			self::displayDevelopmentError($e);
		}
		// Log the exception details.
		Logger::logError($e);
		// Stop script execution.
		exit;
	}

	/**
	 * Custom error handler to handles a PHP error.
	 *
	 * Converts PHP errors into ErrorExceptions and 
	 * throws them to be handled by the exception handler.
	 *
	 * @param int $errorNo The error number.
	 * @param string $errorString The error message.
	 * @param string $errorFile The file where the error occurred.
	 * @param int|string $errorLine The line number where the error occurred.
	 * @return void
	 * @throws ErrorException The converted ErrorException.
	 */
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

    // Determine the error type.
    $errorType = isset($errorTypes[$errorNo]) ? $errorTypes[$errorNo] : 'Unknown Error';

    // Throw an ErrorException to be handled by the global exception handler.
    throw new ErrorException("PHP [$errorType]: [$errorString] in [$errorFile] on line [$errorLine]", 0, $errorNo);
	}

	/**
	 * Displays a detailed error message in development mode.
	 *
	 * @param Throwable $e The exception to display.
	 * @return void
	 */
	private static function displayDevelopmentError(Throwable $e): void
	{
		// Use the Response class to send a detailed error response with stack trace.
		Response::errorResponse(500, $e);
	}

	/**
	 * Displays a generic error message in production mode.
	 *
	 * @param Throwable $e The exception (used for logging but not displayed).
	 * @return void
	 */
	private static function displayProductionError(Throwable $e): void
	{
		// Use the Response class to send a generic error response.
		Response::errorResponse(500);
	}

	/**
	 * Configures error reporting based on the application environment.
	 *
	 * @return void
	 */
	private static function setErrorReporting(): void
	{
		// Check if the environment is set to development.
		if (isset(self::$environment) && self::$environment == 'development') {
			// Report all errors except notices in development.
			error_reporting(E_ALL & ~E_NOTICE); // error report all except notices 
		} else {
			// Report only critical errors in production.
			error_reporting(E_ERROR | E_WARNING | E_PARSE);
		}
	}

	/**
	 * Registers the exception and error handlers.
	 *
	 * This method sets the global exception and error handlers to use the methods
	 * of the ExceptionHandler class.
	 *
	 * @param string $environment The application environment.
	 * @return void
	 */
	public static function register(string $environment): void
	{
		// Get the singleton instance of the ExceptionHandler.
		self::$instance = self::instance($environment);

		// Set the exception handler.
		set_exception_handler([self::$instance, 'handleException']); 
		// Set the error handler.
		set_error_handler([self::$instance, 'handleError']);
	}
}