<?php

namespace roots\app\core;

use roots\app\core\Configuration;
use roots\app\core\Cookie;
use \Execption;

/**
 * Session Class
 */
class Session
{
	// public static Session instance property
	public static Session $instance;

	// public static string session name property
	public static string $session_name;

	function __construct(string $session_id = "")
	{
		self::$instance = $this;
		self::$session_name = Configuration::get('application.session_name');
		session_name(self::$session_name);
		// set if any custom session id
		if (!empty($session_id)) {
			session_id($session_id);
		}
		session_start();
	}

	// public static method for get session instance
	public static function instance(string $session_id = ""): object
	{
		if (!isset(self::$instance)) {
			self::$instance = new self($session_id);
		}

		return self::$instance;
	}

	// public static method for get session value
	public static function get(string $name): mixed
	{
		self::$instance = self::instance();
		return $_SESSION[$name] ?? null;
	}

	// public static method to set session value
	public static function set(string $name, mixed $value): void
	{
		self::$instance = self::instance();
		$_SESSION[$name] = $value;
	}

	// public static method to get session id
	public static function id(string $session_id = ""): string
	{
		self::$instance = self::instance();
		if (empty($session_id)) {
			return session_id();
		}
		return session_id($session_id);
	}

	// public static method to regenerate session id
	public static function regenerateId(): void
	{
		self::$instance = self::instance();
		session_regenerate_id(true);
	}

	// public static method to destroy session
	public static function destroy(): void
	{
		self::$instance = self::instance();
		// overwriting all session variables
		$_SESSION = array();
		// delete session cookie if any
		if (ini_get('session.use_cookies')) {
			$params = session_get_cookie_params();
			Cookie::delete(self::$session_name, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
		}

		// session destroy function
		session_destroy();
	}

}