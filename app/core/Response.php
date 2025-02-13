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

use roots\app\Main;
use roots\app\core\Configuration;
use ReflectionFunction;
use ReflectionMethod;
use Exception;
use Error;

/**
 * Class Response
 *
 * Which has properties and methods to handle response.
 * It has instance, deliver, view, redirect, errorResponse
 * and contentType methods
 *
 */
class Response
{
	
	/**
	 * The singleton instance of the Response class.
	 *
	 * @var Response|null
	 */
	private static ?Response $instance = null;

	/**
	 * The singleton instance of the Configuration class.
	 *
	 * @var Configuration|null
	 */
	private static ?Configuration $configurationInstance = null;

	/**
	 * The singleton instance of the Main class.
	 *
	 * @var Main|null
	 */
	private static ?Main $mainInstance = null;

	/**
	 * The root path of the application.
	 *
	 * @var string
	 */
	private static string $rootPath;

	/**
	 * The namespace for middleware classes.
	 *
	 * @var string
	 */
	private static string $nsMiddleware;

	/**
	 * Constructor for the Response class.
	 *
	 * Initializes the Response instance, configuration, root path, and middleware namespace.
	 *
	 * @throws Exception If the application root path or 
	 * middleware namespace is missing in the configuration.
	 */
	public function __construct()
	{
		self::$instance = $this;
		self::$configurationInstance = Configuration::instance();
		self::$rootPath = Configuration::get('application.root_path');
		self::$nsMiddleware = Configuration::get('application.middleware_namespace');

		if (is_null(self::$rootPath)) {
			throw new Exception("Application Root Path Missing Error", 1);
		}

		if (is_null(self::$nsMiddleware)) {
			throw new Exception("Middleware Namespace Missing Error", 1);
		}
	}

	/**
	 * Returns the singleton instance of the Response class.
	 *
	 * @return Response The singleton instance.
	 */
	public static function instance()
	{
		// Check if an instance already exists.
		if (!isset(self::$instance)) {
			// Create a new instance if one doesn't exist.
			self::$instance = new self;
		}

		// Return the singleton instance.
		return self::$instance;
	}

	/**
	 * Delivers the response based on the provided callback, middleware, and parameters.
	 *
	 * @param callable|string|array|null $callback The callback to execute. 
	 * It can be a callable, a view name (string),
	 * A controller array ([className, methodName]), or null (for 404).
	 * @param string|null $middleware The middleware to execute (optional).
	 * @param array $params The parameters to pass to the callback.
	 * @throws Exception If the middleware class, controller class, or callback argument is missing.
	 */
	public static function deliver(mixed $callback, mixed $middleware, mixed $params): void
	{
		// Get response instance
		self::$instance = self::instance();

		// reflectionReference is used to reflectionClass or Function reference
		$reflectionReference = null;

		// reflectionParams is used for hold controller and function callbacks parameters
		$reflectionParams = [];

		// callbackResult is used to store response from call_user_func_array function	
		$callbackResult = null;	

		// Handle missing callback (404).
		if (is_null($callback)) {
			// implement 404 error page from Response class
			self::errorResponse(404);
			return;
		}

		// Load middleware if exists
		if (!is_null($middleware) && !empty($middleware)) {
			$middlewareClass = self::$nsMiddleware . $middleware;
			if (!class_exists($middlewareClass)) {
				throw new Exception("Middleware Class Not Exist Error", 1);
				die();
			}
			new $middlewareClass;
		}

		// Handle view (string callback)
		if (is_string($callback)) {
			self::view($callback, $params);
			return;
		}

		// Handle controller (array callback).
		if (is_array($callback)) {
			$className = array_shift($callback);
			$methodName = array_shift($callback);

			if (!class_exists($className)) {
				throw new Exception("Controller Class Not Exist Error", 1);
				die();
			}

			try {
				$callbackInstance = new $className;
				$reflectionReference = new ReflectionMethod($callbackInstance, $methodName);
				array_push($callback, $callbackInstance);
				array_push($callback, $methodName);				
			} catch (Exception $e) {
				throw new Exception("Method " . $methodName . " not found in class " . $className);
			}
		}

		// Handle closure (object callback) and 
		// Evaluate code if a callback types is a function
		if (is_object($callback) && is_callable($callback)) { // Check if it's actually callable
			try {
				$reflectionReference = new ReflectionFunction($callback);
			} catch (ReflectionException $e) {
				throw new Exception("Reflection error with callable: " . $e->getMessage());
			}
		} else if (!is_callable($callback)){
        throw new Exception("Invalid callback provided");
    }
		
		// Prepare callback parameters.
		$reflectionParams = $reflectionReference->getParameters();
		$_params = [];

		foreach ($reflectionParams as $param) {
			$paramName = $param->getName();
			if (isset($params[$paramName])) {				
				$_params[] =  $params[$paramName];
			} elseif ($param->isDefaultValueAvailable()) {
				$_params[] = $param->getDefaultValue();
			} else {
				throw new Exception("Callback Argument Missing Error: " . $paramName, 1);
			}
		}

		$params = $_params;
		
		// Execute the callback.
		$callbackResult = call_user_func_array($callback, $params);

		// Handle callback result for JSON.
		if (is_array($callbackResult)) {			
			self::contentType('application/json');
			echo json_encode($callbackResult);
			return;
		}

		// Handle callback result for HTML
		if (is_string($callbackResult)) {			
			echo $callbackResult;
			return;
		}
	}

	/**
	 * Renders a view file.
	 *
	 * @param string $viewFile The path to the view file (relative to the views directory).
	 * @param array $params An array of parameters to pass to the view.
	 * @param int $responseCode The HTTP response code (default: 200).
	 */
	public static function view(string $viewFile, array $params = [], int $responseCode = 200): void
	{
		// Get response instance
		self::$instance = self::instance();

		$viewFile = self::$rootPath . "/public/views/" . trim($viewFile, '/') . ".view.php";

		if (!file_exists($viewFile)) {
			self::errorResponse(404);	
			return;	
		}

		// Response for view file
		http_response_code($responseCode);		
		
		// Extract params
		extract($params);

		require $viewFile;
		return;
	}

	/**
	 * Performs a redirect to the specified URL.
	 *
	 * @param string $urlPath The URL to redirect to.
	 * @param int $statusCode The HTTP status code for the redirect 
	 * (default: 301 - Moved Permanently).
	 */
	public static function redirect(string $urlPath, int $statusCode = 301): void
	{
		header("Location: $urlPath", true, $statusCode);
		exit;
	}

	/**
	 * Sends an error response.
	 *
	 * @param int|string $statusCode The HTTP status code (e.g., 404, 500).
	 * @param mixed ...$args Additional arguments to pass to the error view.  
	 * Typically, this would be an Exception or Error object.
	 * 
	 * @throws Exception If the view file for the given status code is not found.
	 */
	public static function errorResponse(int|string $statusCode, mixed ...$args): void
	{
		// Get error response file path for corresponding statusCode 
		$viewFile = Configuration::get("response.$statusCode");

		// Handles if the response file path is not found
		if (is_null($viewFile)) {
			throw new Exception("View File Not Found Error", 1);
		}		

		http_response_code($statusCode);

		// Extract arguments
		$data = [];
		if (isset($args[0]) && ($args[0] instanceof Error || $args[0] instanceof Exception)) {
			$data = $args[0];
		}

		$e = $data;
		require $viewFile;
		return;
	}

	/**
	 * Sets the Content-Type header.
	 *
	 * @param string $type The content type (e.g., 'application/json', 'text/html').
	 * @throws Exception If the content type is empty.
	 */
	public static function contentType(string $type): void
	{
		if (empty($type)) {
			throw new Exception("Header Content Type Empty Error", 1);
		}

		header("Content-Type: $type");
		return;		
	}
}
