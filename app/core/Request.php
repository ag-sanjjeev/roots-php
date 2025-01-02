<?php

namespace roots\app\core;

/**
 * Request class
 */
class Request
{
	// routes private static property
	private static $routes = [];

	// All request dynamic static array property
	private array $d = [];

	// Request input data static array property
	private static array $inputData = [];

	// Request full url static string property
	private static string $fullUrl = '';

	// Request url path static string property
	private static string $urlPath = '';

	// Request url parametes static array property
	private static array $urlParams = [];

	public function __construct()
	{				
		self::process();
		self::initInputs();
	}

	public function _set(int|string $key, mixed $value = ''): void
	{
		$this->d[$key] = $value;
	}

	public function _get(int|string $key): mixed
	{
		return $this->d[$key] ?? null;
	}

	private static function process(): void
	{
		$serverName = $_SERVER['SERVER_NAME'] ?? '';
		$serverPort = $_SERVER['SERVER_PORT'] ?? '';
		self::$urlPath = $_SERVER['REQUEST_URI'] ?? '/';		
		$urlParams = '';


		self::$fullUrl = $serverName . (!empty($serverPort) ? ":$serverPort" : '') . self::$urlPath;

		$paramsPosition = strpos(self::$urlPath, '?');

		// check for any url parameters
		if ($paramsPosition !== false) {
			$urlChunks = explode('?', self::$urlPath);
			self::$urlPath = array_shift($urlChunks); // gets first item from array
			$urlParams = array_pop($urlChunks); // gets last item from array
		}	

		// url params from string to array conversion
		if (strpos($urlParams, '&')) {
			$urlParams = explode('&', $urlParams);
		}

		if (is_array($urlParams)) {
			array_map(function($item) {
				$parts = explode('=', $item);
				$key = array_shift($parts);
				$value = array_pop($parts);
				self::$urlParams[$key] = $value;
			}, $urlParams);			
		}

	}

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
	}

	public static function input(int|string $key=''): mixed 
	{
		return self::$inputData[$key] ?? null;
	}

	public static function inputsOnly(array|string $keys): mixed
	{
		if (is_string($keys)) {
			$keys = explode(',', $keys);
		}

		// trim any whitespaces when specifying as string type $keys
		$keys = array_map(function($key) {
			return trim($key);
		}, $keys);

		$urlParams = self::$urlParams;

		$extractedParams = array_map(function($key) use ($urlParams) {
			return [$key => $urlParams[$key] ?? null];
		}, $keys);

		$urlParams = array_merge(...$extractedParams);

		return $urlParams;
	}

	public static function inputsExcept(array|string $keys): mixed
	{
		if (is_string($keys)) {
			$keys = explode(',', $keys);
		}

		// trim any whitespaces when specifying as string type $keys
		$keys = array_map(function($key) {
			return trim($key);
		}, $keys);

		$urlParams = self::$urlParams;

		return array_diff_key($urlParams, array_flip($keys));
	}

	public static function hasInput(int|string $key): bool
	{
		return array_key_exists($key, self::$urlParams);
	}

	public static function missingInputs(array|string $keys): array|bool
	{
		if (is_string($keys)) {
			$keys = explode(',', $keys);
		}		

		// trim any whitespaces when specifying as string type $keys
		$keys = array_map(function($key) {
			return trim($key);
		}, $keys);

		$missingKeys = array_diff($keys, array_keys(self::$urlParams));

		return empty($missingKeys) ? false : $missingKeys;
	}

	public static function method(): string|null
	{
		return $_SERVER['REQUEST_METHOD'] ?? null;
	}

	public static function isGet(): bool
	{
		return strtolower(self::method()) == 'get';
	}

	public static function isPost(): bool
	{
		return strtolower(self::method()) == 'post';
	}

	public static function urlPath(): string
	{
		return self::$urlPath;
	}

	public static function fullUrl(): string
	{
		return self::$fullUrl;
	}

	public static function urlParams(int|string $key): mixed
	{
		return self::$urlParams[$key] ?? null;
	}

	public static function ip(): string|null
	{
		return $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? null;		
	}

	public static function acceptableContentType(): mixed
	{
		return explode(',', trim($_SERVER['HTTP_ACCEPT'] ?? ''));
	}

	public static function isAcceptableContentType(array|string $contentTypes): bool
	{
		if (is_string($contentTypes)) {
			$contentTypes = explode(',', $contentTypes);
		}

		// trim any whitespaces when specifying as string type $contentTypes
		$contentTypes = array_map(function($type) {
			return trim($type);
		}, $contentTypes);

		$acceptableContentType = self::acceptableContentType();

		return !empty(array_intersect($acceptableContentType, $contentTypes));
	}	
}