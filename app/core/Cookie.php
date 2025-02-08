<?php

namespace roots\app\core;

use \Exception;

/**
 * Cookie
 */
class Cookie
{
	
	// public static method for set cookie
	public static function set(string $name, string $value, int $expire, string $path = "", string $domain = "", bool $secure = false, bool $httpOnly = false): bool
	{
		if (empty($name) || empty($value) || empty($expire)) {
			throw new Exception("Missing cookie parameters", 1);
			return false;
		}

		return setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
	}

	// public static method for get cookie
	public static function get(string $name): mixed
	{
		return $_COOKIE[$name] ?? null;
	}

	// public static method for update cookie
	public static function update(string $name, string $value, int $expire): bool
	{
		if (empty($name) || empty($value) || empty($expire)) {
			throw new Exception("Missing cookie parameters", 1);
			return false;
		}

		return setcookie($name, $value, $expire);
	}

	// public static method for delete cookie
	public static function delete(string $name, string $path = "", string $domain = "", bool $secure = false, bool $httpOnly = false): bool
	{
		if (empty($name)) {
			throw new Exception("Missing cookie parameters", 1);
			return false;
		}

		return setcookie($name, '', time() - 3600, $path, $domain, $secure, $httpOnly);
	}
}