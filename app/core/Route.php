<?php

namespace roots\app\core;

/**
 * Route class
 */
class Route
{
	
	// Route instance static property
	private static Route $routeInstance;

	// Route method static property
	private static string $_method;

	// Route routePath static property
	private static string $_routePath;

	// Route callback static property
	private static string $_callback;

	// Route lastIndex static property
	private static string $_lastIndex;

	// routes array static property
	private static array $routes = [];

	public function __construct()
	{
		self::$routeInstance = $this;
		$this->acquireRoutes();

		echo "<pre>";
		print_r(self::$routes);
		echo "</pre>";
	}

	public function implement(): void
	{
		echo "Route implemented";
	}

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

	public static function get(string $routePath, mixed $callback): object
	{
		self::$_routePath = $routePath;
		self::$_callback = $callback;
		self::$_method = 'get';

		self::$routes[self::$_method][$routePath]['callback'] = $callback;

		return self::$routeInstance;
	}

	public static function post(string $routePath, mixed $callback): object
	{
		self::$_routePath = $routePath;
		self::$_callback = $callback;
		self::$_method = 'post';

		self::$routes[self::$_method][$routePath]['callback'] = $callback;		

		return self::$routeInstance;
	}

	public static function any(string $routePath, mixed $callback): object
	{
		self::$_routePath = $routePath;
		self::$_callback = $callback;
		self::$_method = 'any';

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