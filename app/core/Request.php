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

/**
 * Class Request
 *
 * Which has properties and methods to access configurations.
 * It has instance, _set, _get, process, initInputs, protocol
 * host, baseURL, input, inputsOnly, inputsExcept, hasInput
 * missingInputs, method, isGet, isPost, urlPath, fullUrl
 * urlParams, ip, acceptableContentType and isAcceptableContentType methods
 *
 */
class Request
{
	/**
	 * The singleton instance of the Request class.
	 *
	 * @var Request|null
	 */
	private static ?Request $instance = null;

	/**
	 * An array to store dynamic property data
	 *
	 * @var array
	 */
	private array $d = [];

	/**
	 * Stores the raw input data from the request (e.g., GET, POST and FILES)
	 *
	 * @var array
	 */
	private static array $inputData = [];

	/**
	 * The full URL of the current request.
	 *
	 * @var string
	 */
	private static string $fullUrl = '';

	/**
	 * The URL path of the current request (excluding the host details).
	 *
	 * @var string
	 */
	private static string $urlPath = '';

	/**
	 * An array containing the URL parameters (parts of the request fullUrl).
	 *
	 * @var array
	 */
	private static array $urlParams = [];

	/**
	 * Constructor for the Request class.
	 *
	 * Initializes the Request instance, processes the request,
	 * and initializes the input data by populating data from superglobals.
	 */
	public function __construct()
	{
		self::$instance = $this;		
		self::process(); // Process the current request
		self::initInputs(); // Initialize raw input data.
	}

	/**
	 * Returns the singleton instance of the Request class.
	 *
	 * @return static The singleton instance.
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
	 * Sets a value in the dynamic data array.
	 *
	 * @param int|string $key The key to set.
	 * @param mixed $value The value to set.
	 * @return void
	 */
	public function _set(int|string $key, mixed $value = ''): void
	{
		$this->d[$key] = $value;
	}

	/**
	 * Retrieves a value from the dynamic data array.
	 *
	 * @param int|string $key The key to retrieve.
	 * @return mixed The value associated with the key, or null if the key does not exist.
	 */
	public function _get(int|string $key): mixed
	{
		return $this->d[$key] ?? null;
	}

	/**
	 * Processes the request, populating URL information and parameters.
	 *
	 * This method extracts the full URL, URL path, and URL parameters 
	 * and populates the corresponding properties of the Request class.
	 */
	private static function process(): void
	{
		// Sets various URL parts
		$serverName = $_SERVER['SERVER_NAME'] ?? '';
		$serverPort = $_SERVER['SERVER_PORT'] ?? '';
		self::$urlPath = $_SERVER['REQUEST_URI'] ?? '/';		
		$urlParams = '';

		// Sets fullUrl
		self::$fullUrl = $serverName . (!empty($serverPort) ? ":$serverPort" : '') . self::$urlPath;

		// Extract URL parameters.
		$paramsPosition = strpos(self::$urlPath, '?');

		// Check for any URL parameters
		if ($paramsPosition !== false) {
			$urlChunks = explode('?', self::$urlPath);
			self::$urlPath = array_shift($urlChunks); // gets first item from array
			$urlParams = array_pop($urlChunks); // gets last item from array
		}	

		// Convert URL parameters from string to array conversion
		if (strpos($urlParams, '&')) {
			$urlParams = explode('&', $urlParams);
		}

		// Extract and populates URL parameters
		if (is_array($urlParams)) {
			array_map(function($item) {
				$parts = explode('=', $item);
				$key = array_shift($parts);
				$value = array_pop($parts);
				self::$urlParams[$key] = $value;
			}, $urlParams);			
		}
	}

	/**
	 * Initializes the input data from superglobals ($_GET, $_POST, $_FILES).
	 *
	 * This method populates the `$inputData` array with data from the superglobals.
	 */
	private static function initInputs(): void
	{
		// get request method input
		foreach ($_GET as $key => $value) {
			self::$inputData[$key] = $value;
		}

		// get request method input
		foreach ($_POST as $key => $value) {
			self::$inputData[$key] = $value;
		}

		// file uploaded input
		foreach ($_FILES as $key => $value) {
			self::$inputData[$key] = $value;
		}
	}

	/**
	 * Returns the current request protocol (http or https).
	 *
	 * @return string The protocol (http or https).
	 */
	public static function protocol(): string
	{
		return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
	}

	/**
	 * Returns the current host (including the port if it's non-standard).
	 *
	 * @return string The host (e.g., example.com or example.com:8080).
	 */
	public static function host(): string
	{
		$serverName = $_SERVER['SERVER_NAME'] ?? '';
		$serverPort = $_SERVER['SERVER_PORT'] ?? '';

		return empty($serverPort) || $serverPort == 80 || $serverPort == 443 ? $serverName : sprintf("%s:%d", $serverName, $serverPort);
	}

	/**
	 * Returns the base URL of the application (protocol and host).
	 *
	 * @return string The base URL (e.g., https://example.com).
	 */
	public static function baseURL(): string
	{
		$protocol = self::protocol();
		$host = self::host();

		return sprintf("%s://%s", $protocol, $host);
	}

	/**
	 * Retrieves an input value from inputData array.
	 *
	 * @param int|string $key The key of the input value to retrieve. 
	 * If empty, returns all input data.
	 * @return mixed The input value, or null if the key does not exist.
	 */
	public static function input(int|string $key=''): mixed 
	{
		// Get request instance
		self::$instance = self::instance();
		return self::$inputData[$key] ?? null;
	}

	/**
	 * Retrieves only the specified input values.
	 *
	 * @param array|string $keys An array or comma-separated string of keys to retrieve.
	 * @return array An associative array containing the specified input values, 
	 * or null if a key does not exist.
	 */
	public static function inputsOnly(array|string $keys): mixed
	{		
		// Get request instance
		self::$instance = self::instance();

		// Convert comma separated keys into array
		if (is_string($keys)) {
			$keys = explode(',', $keys);
		}

		// trim any whitespaces when specifying as string type $keys
		$keys = array_map(function($key) {
			return trim($key);
		}, $keys);

		$urlParams = self::$urlParams;

		// Extract inputData array based on given keys
		$extractedParams = array_map(function($key) use ($urlParams) {
			return [$key => $urlParams[$key] ?? null];
		}, $keys);

		// Merge extracted array of parameters
		$urlParams = array_merge(...$extractedParams);

		return $urlParams; // returns inputData correspond to the keys
	}

	/**
	 * Retrieves all input values except the specified ones.
	 *
	 * @param array|string $keys An array or comma-separated string of keys to exclude.
	 * @return array An associative array containing all input values except the specified keys.
	 */
	public static function inputsExcept(array|string $keys): mixed
	{
		// Get request instance
		self::$instance = self::instance();

		// Converts comma separated keys into array
		if (is_string($keys)) {
			$keys = explode(',', $keys);
		}

		// Trim any whitespaces when specifying as string type $keys
		$keys = array_map(function($key) {
			return trim($key);
		}, $keys);

		$urlParams = self::$urlParams;

		// Returns excluded inputData values
		return array_diff_key($urlParams, array_flip($keys));
	}

	/**
	 * Checks if an input value exists from inputData.
	 *
	 * @param int|string $key The key to check.
	 * @return bool True if the input value exists, false otherwise.
	 */
	public static function hasInput(int|string $key): bool
	{
		// Get request instance
		self::$instance = self::instance();
		// Returns true if key is exist and false if not exist
		return array_key_exists($key, self::$urlParams);
	}

	/**
	 * Checks for missing input values from inputData.
	 *
	 * @param array|string $keys An array or comma-separated string of keys to check.
	 * @return array|bool An array of missing keys, or false if all keys are present.
	 */
	public static function missingInputs(array|string $keys): array|bool
	{
		// Get request instance
		self::$instance = self::instance();

		// Converts comma separated string into array
		if (is_string($keys)) {
			$keys = explode(',', $keys);
		}		

		// Trim any whitespaces when specifying as string type $keys
		$keys = array_map(function($key) {
			return trim($key);
		}, $keys);

		// Extracting missingKeys if any
		$missingKeys = array_diff($keys, array_keys(self::$urlParams));

		// Returns missingKeys or false if all presents.
		return empty($missingKeys) ? false : $missingKeys;
	}

	/**
	 * Returns the request method (e.g., GET, POST, PUT, DELETE).
	 *
	 * @return string|null The request method, or null if it cannot be determined.
	 */
	public static function method(): string|null
	{
		return $_SERVER['REQUEST_METHOD'] ?? null;
	}

	/**
	 * Checks if the request method is GET.
	 *
	 * @return bool True if the request method is GET, false otherwise.
	 */
	public static function isGet(): bool
	{
		return strtolower(self::method()) == 'get';
	}

	/**
	 * Checks if the request method is POST.
	 *
	 * @return bool True if the request method is POST, false otherwise.
	 */
	public static function isPost(): bool
	{
		return strtolower(self::method()) == 'post';
	}

	/**
	 * Returns the URL path of the request (excluding the query string).
	 *
	 * @return string The URL path.
	 */
	public static function urlPath(): string
	{
		// Get request instance
		self::$instance = self::instance();
		return self::$urlPath;
	}

	/**
	 * Returns the full URL of the request.
	 *
	 * @return string The full URL.
	 */
	public static function fullUrl(): string
	{
		// Get request instance
		self::$instance = self::instance();
		return self::$fullUrl;
	}

	/**
	 * Retrieves a URL parameter.
	 *
	 * @param int|string $key The key of the URL parameter to retrieve.
	 * @return mixed The URL parameter value, or null if the key does not exist.
	 */
	public static function urlParams(int|string $key): mixed
	{
		// Get request instance
		self::$instance = self::instance();
		return self::$urlParams[$key] ?? null;
	}

	/**
	 * Returns the client's IP address.
	 *
	 * This method attempts to determine the client's IP address, checking various
	 * headers that might contain this information.
	 *
	 * @return string|null The client's IP address, or null if it cannot be determined.
	 */
	public static function ip(): string|null
	{
		return $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? null;		
	}

	/**
	 * Returns the acceptable content types from the Accept header.
	 *
	 * @return array An array of acceptable content types.
	 */
	public static function acceptableContentType(): mixed
	{
		return explode(',', trim($_SERVER['HTTP_ACCEPT'] ?? ''));
	}

	/**
	 * Checks if the request accepts the given content type(s).
	 *
	 * @param array|string $contentTypes An array or 
	 * comma-separated string of content types to check.
	 * @return bool True if the request accepts at least one of the given content types, 
	 * false otherwise.
	 */
	public static function isAcceptableContentType(array|string $contentTypes): bool
	{
		if (is_string($contentTypes)) {
			$contentTypes = explode(',', $contentTypes);
		}

		// Trim any whitespaces when specifying as string type $contentTypes
		$contentTypes = array_map(function($type) {
			return trim($type);
		}, $contentTypes);

		// Get all acceptable content types
		$acceptableContentType = self::acceptableContentType();

		// Checks whether given content types is acceptable.
		return !empty(array_intersect($acceptableContentType, $contentTypes));
	}	
}