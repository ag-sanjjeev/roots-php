<?php

namespace roots\app\core;

use roots\app\Main;
use roots\app\core\Configuration;
use ReflectionFunction;
use ReflectionMethod;
use Exception;
use Error;

/**
 * Response class
 */
class Response
{
	
	// Response instance private static property
	private static Response $instance;

	// Configuration instance private static property
	private static Configuration $configurationInstance;

	// Main application instance private static property
	private static Main $mainInstance;

	// Root path private static property
	private static string $rootPath;

	// Middleware namespace private static property
	private static string $nsMiddleware;

	// Response Controller private static property 
	private static $responseController;

	public function __construct()
	{
		self::$instance = $this;
		self::$configurationInstance = Configuration::instance();
		self::$rootPath = Configuration::get('application.root_path');
		self::$nsMiddleware = Configuration::get('application.middleware_namespace');

		if (is_null(self::$rootPath)) {
			throw new Exception("Application Root Path Missing Error", 1);
			die();
		}

		if (is_null(self::$nsMiddleware)) {
			throw new Exception("Middleware Namespace Missing Error", 1);
			die();
		}

	}

	public static function instance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	// public __call magic method to identify invoked methods
	public function __call($name, $arguments)
	{
		
	}

	public static function deliver(mixed $callback, mixed $middleware, mixed $params): void
	{
		self::$instance = self::instance();

		// reflectionReference is used to reflectionClass or Function reference
		$reflectionReference = null;

		// reflectionParams is used for hold controller and function callbacks parameters
		$reflectionParams = [];

		// callbackResult is used to store response from call_user_func_array function	
		$callbackResult = null;	

		// No callback exist for the request then treat this urlpath as a 404 error 
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

		// load view if a callback is a string
		if (is_string($callback)) {
			self::view($callback, $params);
			return;
		}

		// load controller if a callback is an array
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
				die($e->getMessage());
			}

		}

		// evaluate code if a callback is a function that treats as closure object
		if (is_object($callback)) {
			try {
				$reflectionReference = new ReflectionFunction($callback);
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}
		
		// assign required parameters that accept as arguments
		$reflectionParams = $reflectionReference->getParameters();
		$_params = [];

		foreach ($reflectionParams as $param) {
			$paramName = $param->getName();
			if (isset($params[$paramName])) {				
				$_params[] =  $params[$paramName];
			} elseif ($param->isDefaultValueAvailable()) {
				$_params[] = $param->getDefaultValue();
			} else {
				throw new Exception("Callback Argument Missing Error", 1);
				die();
			}
		}

		$params = $_params;
		
		// call user function or class method with parameters
		$callbackResult = call_user_func_array($callback, $params);

		// If the callback result is an array then it might be a json response
		if (is_array($callbackResult)) {			
			self::contentType('application/json');
			print_r($callbackResult);
			return;
		}

		// If the callback result is a string then it might be a html response
		if (is_string($callbackResult)) {			
			echo $callbackResult;
			return;
		}
	}

	public static function view(string $viewFile, array $params = [], int $responseCode = 200): void
	{
		self::$instance = self::instance();

		$viewFile = self::$rootPath . "/public/views/" . trim($viewFile, '/') . ".view.php";

		if (!file_exists($viewFile)) {
			self::errorResponse(404);	
			return;	
		}

		// response for found view file
		http_response_code($responseCode);		
		
		// extract params
		extract($params);

		require $viewFile;
		return;
	}

	/**
	* Redirect Method that redirect with statuscode
	* $statusCode 301 defines the location permanently moved
	*/
	public static function redirect(string $urlPath, int $statusCode = 301): void
	{
		header("Location: $urlPath", true, $statusCode);
		exit;
	}

	public static function errorResponse(int|string $statusCode, mixed ...$args): void
	{
		$viewFile = Configuration::get("response.$statusCode");
		if (is_null($viewFile)) {
			throw new Exception("View File Not Found Error", 1);
			die();
		}		

		http_response_code($statusCode);

		// extract arguments
		$data = [];
		if (isset($args[0]) && ($args[0] instanceof Error || $args[0] instanceof Exception)) {
			$data = $args[0];
		}
		extract(['e' => $data]);
		require $viewFile;
		return;
	}

	public static function contentType(string $type): void
	{
		if (empty($type)) {
			throw new Exception("Header Content Type Empty Error", 1);
			die();
		}

		header("Content-Type:$type");
		return;		
	}
}
