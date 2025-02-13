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

use roots\app\core\Configuration;
use roots\app\core\Cookie;
use \Execption;

/**
 * Class Session
 *
 * Which has properties and methods to handle session.
 * It has instance, get, set, id, regenerateId and destroy methods
 *
 */
class Session
{
	/**
	 * The singleton instance of the Session class.
	 *
	 * @var Session|null
	 */
	public static ?Session $instance = null;

	/**
	 * The session name to set session details under this name.
	 *
	 * @var string
	 */
	public static string $session_name;

	/**
	 * Constructor for the Session class.
	 *
	 * Initializes the session, sets the session name, and starts the session.
	 *
	 * @param string $session_id An optional custom session ID.
	 * @throws Exception if session_name is not defined in the configuration
	 */
	function __construct(string $session_id = "")
	{
		self::$instance = $this;
		self::$session_name = Configuration::get('application.session_name');
		if (empty(self::$session_name)) {
        throw new Exception("Session name is not configured.", 1); 
    }
		session_name(self::$session_name);
		// set if any custom session id
		if (!empty($session_id)) {
			session_id($session_id);
		}
		session_start();
	}

	/**
	 * Returns the singleton instance of the Session class.
	 *
	 * @param string $session_id An optional custom session ID.
	 * @return static The singleton instance.
	 */
	public static function instance(string $session_id = ""): object
	{
		// Check if an instance already exists.
		if (!isset(self::$instance)) {
			// Create a new instance if one doesn't exist.
			self::$instance = new self($session_id);
		}

		// Return the singleton instance.
		return self::$instance;
	}

	/**
	 * Retrieves a value from the session.
	 *
	 * @param string $name The name of the session variable to retrieve.
	 * @return mixed The value of the session variable, or null if it is not set.
	 */
	public static function get(string $name): mixed
	{
		// Get session instance
		self::$instance = self::instance();
		return $_SESSION[$name] ?? null;
	}

	/**
	 * Sets a value in the session.
	 *
	 * @param string $name The name of the session variable to set.
	 * @param mixed $value The value to set.
	 */
	public static function set(string $name, mixed $value): void
	{
		// Get session instance
		self::$instance = self::instance();
		$_SESSION[$name] = $value;
	}

	/**
	 * Gets or sets the current session ID.
	 *
	 * If no `$session_id` is provided, this method returns the current session ID.
	 * If a `$session_id` is provided, this method sets the session ID and 
	 * returns the (possibly new) session ID.
	 *
	 * @param string $session_id An optional session ID to set.
	 * @return string The current session ID.
	 */
	public static function id(string $session_id = ""): string
	{
		// Get session instance
		self::$instance = self::instance();
		// Returns the session id 
		if (empty($session_id)) {
			return session_id();
		}
		// Sets new session id
		return session_id($session_id);
	}

	/**
	 * Regenerates the session ID.
	 *
	 * This method regenerates the session ID to prevent session fixation attacks. 
	 * The old session data is retained.
	 */
	public static function regenerateId(): void
	{
		// Get session instance
		self::$instance = self::instance();
		session_regenerate_id(true);
	}

	/**
	 * Destroys the current session.
	 *
	 * This method destroys the session, removes all session variables, 
	 * and deletes the session cookie.
	 */
	public static function destroy(): void
	{
		// Get session instance
		self::$instance = self::instance();

		// Overwriting all session variables
		$_SESSION = array();

		// Delete session cookie if any
		if (ini_get('session.use_cookies')) {
			$params = session_get_cookie_params();
			Cookie::delete(self::$session_name, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
		}

		// Destroy the session
		session_destroy();
	}

}