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
use roots\app\core\Request;
use roots\app\core\Response;

/**
 * Class Route
 *
 * Which has properties and methods to handle routes.
 * It has acquireRoutes, callbackDetails, get, post, any,
 * middleware and name methods
 *
 */
class Route
{
	
	/**
	 * The singleton instance of the Route class.
	 *
	 * @var Route|null
	 */
	private static ?Route $routeInstance = null;

	/**
	 * The singleton instance of the Request class.
	 *
	 * @var Request|null
	 */
	private static ?Request $requestInstance = null;

	/**
	 * The singleton instance of the Response class.
	 *
	 * @var Response|null
	 */
	private static ?Response $responseInstance = null;

	/**
	 * The HTTP request method (e.g., GET, POST).
	 *
	 * @var string
	 */
	private static string $_method;

	/**
	 * The current route path being processed.
	 *
	 * @var string
	 */
	private static string $_routePath;

	/**
	 * The callback associated with the current route.  
	 * This can be a callable, a string (view name), 
	 * or an array (controller and method).
	 *
	 * @var callable|string|array|null
	 */
	private static mixed $_callback = null;

	/**
	 * An array to store defined routes. 
	 * The keys of this array are route patterns,
	 * and the values are the corresponding callbacks,
	 * middlewares and parameters.
	 *
	 * @var array<string, callable|string|array>
	 */
	private static array $routes = [];

	/**
	 * Constructor for the Route class.
	 *
	 * Initializes routes by acquires the defined routes.
	 */
	public function __construct()
	{
		self::$routeInstance = $this;
		$this->acquireRoutes();
	}

	/**
	 * Loads the different route files from route directory.
	 *
	 * @return void
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
	 * Prepares callback details for the current request URL and method.
	 *
	 * This method determines the appropriate callback, middleware, and parameters
	 * based on the defined routes and the current request.
	 *
	 * @return array<string|callable|null, string|callable|null, array|null> An array containing:
	 *         - The callback (callable, string for view, or null).
	 *         - The middleware (callable, string, or null).
	 *         - The parameters to pass to the callback (array or null).
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

	/**
	 * Defines a GET route.
	 *
	 * @param string $routePath The route path (e.g., '/users', '/products/{id}').
	 * @param callable|string|array $callback The callback to execute when the route is matched.
	 * It can be a callable, a string (view name), or an array (controller class and method).
	 *
	 * @return static Returns the Route instance for method chaining.
	 */
	public static function get(string $routePath, mixed $callback): static
	{
		self::$_routePath = $routePath;
		self::$_callback = $callback;
		self::$_method = 'GET';

		self::$routes[self::$_method][$routePath]['callback'] = $callback;

		return self::$routeInstance;
	}

	/**
	 * Defines a POST route.
	 *
	 * @param string $routePath The route path (e.g., '/users', '/products/{id}').
	 * @param callable|string|array $callback The callback to execute when the route is matched.
	 * It can be a callable, a string (view name), or an array (controller class and method).
	 *
	 * @return static Returns the Route instance for method chaining.
	 */
	public static function post(string $routePath, mixed $callback): static
	{
		self::$_routePath = $routePath;
		self::$_callback = $callback;
		self::$_method = 'POST';

		self::$routes[self::$_method][$routePath]['callback'] = $callback;		

		return self::$routeInstance;
	}

	/**
	 * Defines a any HTTP request method route.
	 *
	 * @param string $routePath The route path (e.g., '/users', '/products/{id}').
	 * @param callable|string|array $callback The callback to execute when the route is matched.
	 * It can be a callable, a string (view name), or an array (controller class and method).
	 *
	 * @return static Returns the Route instance for method chaining.
	 */
	public static function any(string $routePath, mixed $callback): object
	{
		self::$_routePath = $routePath;
		self::$_callback = $callback;
		self::$_method = 'ANY';

		self::$routes[self::$_method][$routePath]['callback'] = $callback;		

		return self::$routeInstance;
	}

	/**
	 * Assigns middleware to the current route.
	 *
	 * @param string $middleware The name of the middleware to assign.
	 * @return static Returns the Route instance for method chaining.
	 */
	public static function middleware(string $middleware): static
	{
		self::$routes[self::$_method][self::$_routePath]['middleware'] = $middleware;

		return self::$routeInstance;
	}

	/**
	 * Assigns a name to the current route.
	 *
	 * @param string $name The name to assign to the route.
	 * @return static Returns the Route instance for method chaining.
	 */
	public static function name(string $name): static
	{
		self::$routes[self::$_method][self::$_routePath]['name'] = $name;

		return self::$routeInstance;
	}
}