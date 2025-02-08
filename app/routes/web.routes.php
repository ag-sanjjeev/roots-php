<?php

use roots\app\core\Route;
use roots\app\controllers\DemoController;

Route::get('/', 'welcome')->name('home');
Route::get('/function/{id}', function($id) {
	echo " Function Callback and id: $id";
})->name('home');
Route::get('/demo/index', [DemoController::class, 'index'])->name('demo index'); 
Route::get('/demo/form', 'demo/form')->name('demo form'); 
Route::post('/demo/form/upload', [DemoController::class, 'formUpload'])->name('demo form upload'); 
Route::get('/demo/download/{filename}', [DemoController::class, 'downloadFile'])->name('demo file download'); 
Route::get('/demo/delete/{filename}', [DemoController::class, 'deleteFile'])->name('demo file delete'); 
Route::get('/demo/{id}/show', [DemoController::class, 'show'])->name('demo show'); 
/**
	Route::get('/article/{id}/show', 'article/show')->name('article show'); 
	Route::get('/article/{id}/show/', 'article/show')->name('article show'); 

	both are different, that means even a single trailing or leading slashes make difference 
	if the url not found then it delivers response 404 error
*/