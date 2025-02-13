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

use \Exception;

/**
 * Class Cookie
 *
 * Which has properties and methods to handle cookie.
 * It has set, get, update and delete methods
 *
 */
class Cookie
{
	
	/**
	 * Sets a cookie.
	 *
	 * @param string $name The name of the cookie.
	 * @param string $value The value of the cookie.
	 * @param int $expire The expiration time of the cookie in seconds.
	 * @param string $path The path on the server where the cookie will be available.
	 * @param string $domain The domain for which the cookie is valid.
	 * @param bool $secure Indicates if the cookie should only be transmitted over a secure HTTPS connection.
	 * @param bool $httpOnly Indicates if the cookie should be accessible only through the HTTP protocol.
	 * @return bool True on success, false on failure.
	 */
	public static function set(string $name, string $value, int $expire, string $path = "", string $domain = "", bool $secure = false, bool $httpOnly = false): bool
	{
		// Check for required cookie parameters.
		if (empty($name) || empty($value) || empty($expire)) {
			// Throws an exception if required parameters are missing.
			throw new Exception("Missing cookie parameters", 1);
		}

		// Set the cookie.
		return setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
	}

	/**
	 * Retrieves a cookie value.
	 *
	 * @param string $name The name of the cookie to retrieve.
	 * @return mixed The value of the cookie, or null if not found.
	 */
	public static function get(string $name): mixed
	{
		// Return the cookie value or null if it doesn't exist.
		return $_COOKIE[$name] ?? null;
	}

	/**
	 * Updates an existing cookie.
	 *
	 * @param string $name The name of the cookie to update.
	 * @param string $value The new value of the cookie.
	 * @param int $expire The new expiration time of the cookie in seconds.
	 * @param string $path The path of the cookie.
	 * @param string $domain The domain of the cookie.
	 * @param bool $secure Whether the cookie is secure.
	 * @param bool $httpOnly Whether the cookie is HTTP only.
	 * @return bool True on success, false on failure.
	 * @throws Exception If required parameters are missing.
	 */
	public static function update(string $name, string $value, int $expire, string $path = "", string $domain = "", bool $secure = false, bool $httpOnly = false): bool
	{
		// Check for required cookie parameters.
		if (empty($name) || empty($value) || empty($expire)) {
			// Throw an exception if required parameters are missing.
			throw new Exception("Missing cookie parameters", 1);
		}

		// Update the cookie.
		return setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
	}

	/**
	 * Deletes a cookie.
	 *
	 * @param string $name The name of the cookie to delete.
	 * @param string $path The path of the cookie.
	 * @param string $domain The domain of the cookie.
	 * @param bool $secure Whether the cookie is secure.
	 * @param bool $httpOnly Whether the cookie is HTTP only.
	 * @return bool True on success, false on failure.
	 * @throws Exception If required parameters are missing.
	 */
	public static function delete(string $name, string $path = "", string $domain = "", bool $secure = false, bool $httpOnly = false): bool
	{
		// Check if the cookie name is provided.
		if (empty($name)) {
			// Throw an exception if the cookie name is missing.
			throw new Exception("Missing cookie parameters", 1);
		}
		
		// Delete the cookie by setting its expiration time to the past.
		return setcookie($name, '', time() - 3600, $path, $domain, $secure, $httpOnly);
	}
}