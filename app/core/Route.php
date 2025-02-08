<?php

namespace roots\app\core;

use roots\app\Main;
use roots\app\core\Request;
use roots\app\core\Response;
/**
 * Route class
 */
class Route
{
	
	// Route instance static property
	private static Route $routeInstance;

	// Request instance static property
	private static Request $requestInstance;

	// Response instance static property
	private static Response $responseInstance;

	// Route method static property
	private static string $_method;

	// Route routePath static property
	private static string $_routePath;

	// Route callback static property
	private static $_callback;

	// Route lastIndex static property
	private static string $_lastIndex;

	// routes array static property
	private static array $routes = [];

	public function __construct()
	{
		self::$routeInstance = $this;
		// self::$responseInstance = Response::instance();
		// self::$requestInstance = Request::instance();

		$this->acquireRoutes();
	}

	/**
	* Private function to load user defined route files 
	*/
	private function acquireRoutes(): void
	{
		// setting routes directory
		$routesDirectory = dirname(__DIR__) . '/routes/';

		// get list of all route files
		$routeFiles = glob($routesDirectory . "*.routes.php");

		// require those route files
		foreach ($routeFiles as $file) {
			require $file;
		}
	}

	/**
	* Private Method to match and extract callback, middleware and urlParameters
	* for given method|ANY and urlPath
	*/
	public static function callbackDetails(string $requestMethod, string $urlPath): array
	{
		$method = $requestMethod;
		$urlPath = $urlPath;
		$callback = null;
		$middleware = null;
		$matchedUrlPath = null;
		$params = [];
		$urlParameters = [];

		$callback = self::$routes[$method][$urlPath]['callback'] ?? self::$routes['any'][$urlPath]['callback'] ?? null;
		$middleware = self::$routes[$method][$urlPath]['middleware'] ?? self::$routes['any'][$urlPath]['middleware'] ?? null;

		// Fetching callback and middleware for current method with URL parameters
		if (is_null($callback)) {
			foreach (self::$routes[$method] as $_urlPath => $value) {
				/* 
					\{ ... }\ group enclosed with curly-braces
				 	(...) captures a group
				 	[^}]+ that contains one or more characters doesn't end with curly-braces
				*/
				/*
					([^/]+) that contains one or more characters doesn't end with forward slash
				*/
				$pattern = '#^' . preg_replace('/\{([^}]+)}/', '([^/]+)', $_urlPath) . '$#';
				preg_match($pattern, $urlPath, $params);
				
				if (!empty($params)) {
					$matchedUrlPath = $_urlPath;
					$callback = self::$routes[$method][$_urlPath]['callback'] ?? null;
					$middleware = self::$routes[$method][$_urlPath]['middleware'] ?? null;
					break;
				}
			}
		}
		
		// Fetching callback and middleware for any method with URL parameters
		if (is_null($callback)) {
			foreach (self::$routes['ANY'] as $_urlPath => $value) {
				/* 
					\{ ... }\ group enclosed with curly-braces
				 	(...) captures a group
				 	[^}]+ that contains one or more characters doesn't end with curly-braces
				*/
				/*
					([^/]+) that contains one or more characters doesn't end with forward slash
				*/
				$pattern = '#^' . preg_replace('/\{([^}]+)}/', '([^/]+)', $_urlPath) . '$#';
				preg_match($pattern, $urlPath, $params);
				
				if (!empty($params)) {
					$callback = self::$routes['ANY'][$_urlPath]['callback'] ?? null;
					$middleware = self::$routes['ANY'][$_urlPath]['middleware'] ?? null;
					break;
				}
			}
		}

		// Extracting And Combine URL Parameter Key with corresponding value
		if (is_array($params) && !empty($params)) {
			$pattern = '#^' . preg_replace('/\{([^}]+)}/', '([^/]+)', $matchedUrlPath) . '$#';
			$paramsFound = [];
			preg_match_all($pattern, $matchedUrlPath, $paramsFound);
			
			array_shift($params); // remove first element of the urlParameters that has urlPath
			array_shift($paramsFound); // remove first element of the urlParameters that has urlPath

			$_paramsKey = [];
			array_walk_recursive($paramsFound, function ($keyName, $index) use (&$_paramsKey) {				
				$_paramsKey[] = trim($keyName, '{}'); // remove curly-braces around keyName
			});

			// combine urlParameters with parameter values
			$urlParameters = array_combine(array_values($_paramsKey), array_values($params));
		}

		$callback = $callback ?? null;
		$middleware = $middleware ?? null;
		$urlParameters = $urlParameters ?? null;	

		// Return Action Details callback, middleware and urlParameters
		return ['callback' => $callback, 'middleware' => $middleware, 'urlParameters' => $urlParameters];
	}

	public static function get(string $routePath, mixed $callback): object
	{
		self::$_routePath = $routePath;
		self::$_callback = $callback;
		self::$_method = 'GET';

		self::$routes[self::$_method][$routePath]['callback'] = $callback;

		return self::$routeInstance;
	}

	public static function post(string $routePath, mixed $callback): object
	{
		self::$_routePath = $routePath;
		self::$_callback = $callback;
		self::$_method = 'POST';

		self::$routes[self::$_method][$routePath]['callback'] = $callback;		

		return self::$routeInstance;
	}

	public static function any(string $routePath, mixed $callback): object
	{
		self::$_routePath = $routePath;
		self::$_callback = $callback;
		self::$_method = 'ANY';

		self::$routes[self::$_method][$routePath]['callback'] = $callback;		

		return self::$routeInstance;
	}

	public static function middleware(string $middleware): object
	{
		self::$routes[self::$_method][self::$_routePath]['middleware'] = $middleware;

		return self::$routeInstance;
	}

	public static function name(string $name): object
	{
		self::$routes[self::$_method][self::$_routePath]['name'] = $name;

		return self::$routeInstance;
	}
}