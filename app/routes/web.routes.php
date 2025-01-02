<?php

use roots\app\core\Route;

Route::get('/', 'home page')->name('index');
Route::get('/article/{id}/show', 'article with id')->name('article page');