<?php

/**
 * ROOTS PHP MVC FRAMEWORK
 *
 * This is an entry point for application.
 * This file will loads composer autoload class to load all classes under defined namespace.
 *
 * LICENSE: This Framework and all source file is subject to license. Refer License File
 * at https://github.com/ag-sanjjeev. If you did not receive a complete copy of it, Then
 * get  it  from  the  project  repository  at  https://github.com/ag-sanjjeev/roots-php. 
 * 
 * Follow instructions as per license and README.
 *
 * @category Framework
 * @author ag-sanjjeev 
 * @copyright 2025 ag-sanjjeev
 * @license https://github.com/ag-sanjjeev/roots-php/LICENSE MIT
 * @version Release: @1.0.0@
 * @link https://github.com/ag-sanjjeev/roots-php
 * @since This is available since Release 1.0.0
 */

namespace roots;

use roots\app\Main;
use roots\app\core\Configurations;

// Composer Autoloads
require __DIR__ . './../vendor/autoload.php';

// Application Environment
putenv("APP_ENVIRONMENT=development");

// Application Instance
$main = Main::instance();

// Run Application
$main->run();
