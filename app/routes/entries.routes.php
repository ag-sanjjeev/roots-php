<?php

use roots\app\core\Route;

Route::get('/product', 'product page')->middleware('account')->name('product');
Route::any('/product/{id}/edit', 'product edit page with id')->middleware('auth');