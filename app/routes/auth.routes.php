<?php

use roots\app\core\Route;

Route::get('/dashboard', 'dashboard/index')->middleware('auth')->name('dashboard index');
Route::get('/login', 'user/login')->name('user login');