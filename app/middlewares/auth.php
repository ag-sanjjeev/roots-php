<?php

namespace roots\app\middlewares;

use roots\app\core\Logger;
use roots\app\core\Response;
use roots\app\core\Session;
use \Exception;

/**
 * Auth Middleware
 */
class Auth
{
	
	function __construct()
	{
		$user_id = Session::get('user_id');
		if (is_null($user_id)) {
			// throw new Exception("Unauthorized access", 1);
			// exit;
			Response::redirect('/login');
		}
	}
}