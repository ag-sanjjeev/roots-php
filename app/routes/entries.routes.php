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

use roots\app\core\Route;

// Routes definitions
Route::get('/product', 'product/index')->middleware('account')->name('product index');
Route::any('/product/{id}/edit', 'product/edit')->middleware('auth');