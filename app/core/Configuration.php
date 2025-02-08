<?php

namespace roots\app\core;

/**
 * Configuration class
 */
class Configuration
{
	
	// configuration static property
	private static Configuration $instance;
	
	// environment static property
	private static $environment;

	// settings static property
	private static $settings = [];

	private function __construct()
	{
		self::setEnvironment();	
		self::initSettings();	
	}

	private static function setEnvironment(): void
	{
		if (empty(getenv("APP_ENVIRONMENT"))) {
			self::$environment = 'development';
		} else {
			self::$environment = getenv("APP_ENVIRONMENT");
		}
	}

	private static function initSettings(): void
	{
		$configFile = __DIR__ . "/../configurations/config.php";

		if (!file_exists($configFile)) {
			throw new \Exception("Missing Configuration File");
			die("Unable to run application");
		}

		self::$settings = require $configFile;		
	}

	public static function instance(): object
	{
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public static function get(string $key, mixed $default = null): mixed
	{
		self::$instance = self::instance();
		return array_reduce(explode('.', $key), function ($additional, $part) use ($default) {				
			return $additional[$part] ?? $default;
		}, self::$settings);
	}
}
