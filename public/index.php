<?php

/**
 * Framework entry point
 *
 * This is an entry point for all request.
 * This file will loads composer autoload class to load all classes under `app/` namespace at top.
 *
 * Require PHP version 8.1 and above
 *
 * LICENSE: This Framework and all source file is subject to license. Refer License File.
 * at https://github.com/ag-sanjjeev. If you did not receive a complete copy of it.
 * Then get it from the project repository at https://github.com/ag-sanjjeev/roots-php. Please send a note through the author via contact details found in README for any changes made in this project. For any other
 * instructions to be followed as per license and README.
 *
 * @category Framework
 * @author ag-sanjjeev 
 * @copyright 2024 ag-sanjjeev
 * @license https://github.com/ag-sanjjeev/roots-php/LICENSE MIT LICENSE
 * @version Release: @1.0@
 * @link https://github.com/ag-sanjjeev/roots-php
 * @since This is available since Release 1.0
 */

namespace roots;

use roots\app\Main;
use roots\app\core\Configurations;

// Implement Composer Autoloads
require __DIR__ . './../vendor/autoload.php';

putenv("APP_ENVIRONMENT=development");

$main = Main::instance();
//$obj->showValue(getenv('APP_ENV'));
$main->run();
