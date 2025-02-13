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
 * Class Configuration
 *
 * Which has properties and methods to access configurations.
 * It has setEnvironment, initSettings, instance and get methods
 *
 */
class Configuration
{
	/**
   * Static property this class instance.
   *
   * @var Configuration
   */
	private static Configuration $instance;
	
	/**
	 * Static property environment
   *
   * @var string
   */
	private static string $environment;

	/**
	 * Static property settings
   *
   * @var array
   */
	private static array $settings = [];
	
	/**
   * Constructs a new Configuration object.
   */
	private function __construct()
	{
		// sets application environment
		self::setEnvironment();	
		// initiates settings from configurations
		self::initSettings();	
	}

	/**
	 * Sets the application environment.
	 *
	 * @return void
	 */
	private static function setEnvironment(): void
	{
		// Get environment, if it is not set then it be development 
		if (empty(getenv("APP_ENVIRONMENT"))) {
			self::$environment = 'development';
		} else {
			self::$environment = getenv("APP_ENVIRONMENT");
		}
	}

 /**
	 * Initializes the application settings.
	 *
	 * @return void
	 */
	private static function initSettings(): void
	{
		// Defines the path to the configuration file.
		$configFile = __DIR__ . "/../configurations/config.php";

		// Checks if the configuration file exists.
		if (!file_exists($configFile)) {
			throw new \Exception("Missing Configuration File");
		}

		// Loads the configuration file and stores the settings.
		self::$settings = require $configFile;		
	}

	/**
	 * Returns the singleton instance of the class.
	 *
	 * @return object instance.
	 */
	public static function instance(): object
	{
		// Check if an instance already exists.
		if (!isset(self::$instance)) {
			// Create a new instance if one doesn't exist.
			self::$instance = new self();
		}

		// Return the singleton instance.
		return self::$instance;
	}

	/**
	 * Retrieves a value from the application settings.
	 *
	 * @param string $key The key of the setting to retrieve.
	 * @param mixed $default The default value to return if the key is not found.
	 * @return mixed The value of the setting, or the default value if not found.
	 */
	public static function get(string $key, mixed $default = null): mixed
	{
		// Get the singleton instance.
		self::$instance = self::instance();

		/* 
		 * Use array_reduce to traverse the settings array based on the key 
		 * in the dot separated pattern.
		 * For Example: application.category.key
		 */
		return array_reduce(explode('.', $key), function ($additional, $part) use ($default) {				
			return $additional[$part] ?? $default;
		}, self::$settings);
	}
}
