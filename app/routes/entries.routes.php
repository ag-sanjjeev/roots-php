<?php

use roots\app\core\Route;

Route::get('/product', 'product/index')->middleware('account')->name('product index');
Route::any('/product/{id}/edit', 'product/edit')->middleware('auth');