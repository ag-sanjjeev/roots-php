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

namespace roots\app\middlewares;

use roots\app\core\Logger;
use roots\app\core\Response;
use roots\app\core\Session;
use \Exception;

/**
 * Class Auth
 *
 * Which act as a middleware that apply logic for the current request.
 *
 */
class Auth
{
	/**
	 * Constructor for the Auth middleware class.
	 *
	 * Initializes the auth middleware.
	 */
	function __construct()
	{
		$user_id = Session::get('user_id');
		if (is_null($user_id)) {
			Response::redirect('/login');
		}
	}
}