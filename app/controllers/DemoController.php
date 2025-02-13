<?php
/**
 * ROOTS PHP MVC FRAMEWORK.
 *
 * This file contains methods for the route /demo.
 *
 * @category Framework
 * @author ag-sanjjeev 
 * @copyright 2025 ag-sanjjeev
 * @license https://github.com/ag-sanjjeev/roots-php/LICENSE MIT
 * @version Release: @1.0.0@
 * @link https://github.com/ag-sanjjeev/roots-php
 * @since This is available since Release 1.0.0
 */

namespace roots\app\controllers;

use roots\app\core\Configuration;
use roots\app\core\Request;
use roots\app\core\Response;
use roots\app\core\Database;
use roots\app\models\Demo;
use roots\app\core\Logger;
use roots\app\core\Storage;
use \Exception;

/**
 * Class DemoController
 *
 * Which has methods for handling different request.
 *
 */
class DemoController
{
	/**
   * Constructs a new DemoController object.
   */
	public function __construct()
	{

	}

	/**
   * Handles the index request.
   *
   * @return view.
   */
	public function index()
	{
		$contentType = 'text/html';
		$isAcceptContent = Request::isAcceptableContentType($contentType);

		try {
			if (!$isAcceptContent) {
				throw new Exception("Unacceptable content type", 1);
			}
		} catch (Exception $e) {
			Logger::logWarning($e);
		}
		
		// Storage::instance();
		// Storage::unlink('storage/images');
		// $src = Storage::path('images\blue.png');		
		$src = Storage::filePath('images\blue.png');
		$fileSize = Storage::fileSize('images\blue.png');
		// $rootPath = Configuration::get('application.root_path');
		// $filePath = $rootPath . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'blue.png';
		// $src = $filePath;

		Response::contentType($contentType);		
		return Response::view('demo/index', ['src' => $src, 'fileSize' => $fileSize]);
	}

	/**
   * Handles the form upload request.
   */
	public function formUpload()
	{
		$target = 'upload' . DIRECTORY_SEPARATOR . 'image';
		$imagename = Request::input('imagename');		
		$file = Request::input('image');
		$target .= DIRECTORY_SEPARATOR . $imagename;
		Storage::upload($file, $target);
		echo "file uploaded";
	}

	/**
   * Handles the download file request.
   * @param string $filename.
   */
	public function downloadFile($filename)
	{
		$filePath = 'upload' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . $filename . '.png';
		Storage::download($filePath);
	}

	/**
   * Handles the delete file request.
   * @param string $filename.
   */
	public function deleteFile($filename)
	{
		$filePath = 'upload' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . $filename . '.png';

		Storage::unlink($filePath);
		echo 'file deleted';
	}

	/**
   * Handles the show request.
   *
   * @param int $id.
   * @return view.
   */
	public function show($id)
	{
		$contentType = 'text/html';
		$isAcceptContent = Request::isAcceptableContentType($contentType);

		if (!$isAcceptContent) {
			throw new Exception("Unacceptable content type", 1);
			die();
		}

		Response::contentType($contentType);
		$responseCode = 200;
		// echo Demo::instance() instanceof Model;
		// $result = Demo::select('*')->get();
		// $result = Demo::select('*')->getAll();
		// $result = Demo::select('*')->where(['article_title' => 'test fourth title', 'content' => 'some other content'])->getAll();
		// $result = Demo::select('*')->where(['id' => 4])->get();
		// $result = Demo::select('*')->whereAnd(['id' => 4, 'article_title' => 'test third title'])->get();
		// $result = Demo::select('*')->where(['id' => 4])->whereOr(['id' => 5])->getAll();
		// $result = Demo::select('*')->whereOr(['id' => 4])->whereOr(['id' => 5])->getAll();
		// $result = Demo::select('*')->whereOr(['id' => 4])->whereAnd(['id' => 5])->getAll();
		// $result = Demo::select('*')->whereOr(['id' => 4])->whereAnd(['id' => 5])->getAll();
		// $result = Demo::select('*')->where(['id' => 4])->whereAnd(['article_title' => 'test third title'])->get();
		// $result = Demo::select('*')->where(['id' => 4])->whereOr(['id' => 5])->orderDesc(['id', 'title'], 'content')->getAll();
		// $result = Demo::select('*')->where(['id' => 4])->whereOr(['id' => 5])->orderDesc('content', 'id')->getAll();
		// $result = Demo::select('*')->where(['id' => 4])->whereOr(['id' => 5])->orderAsc('content', 'id')->getAll();
		// $result = Demo::select('*')->where(['id' => 4])->whereOr(['id' => 5])->groupBy('author')->orderAsc('content', 'id')->getAll();
		// $result = Demo::select('*', 'author', 'SUM(views) AS total_views')->getAll();
		// $result = Demo::select('author', 'SUM(views) AS total_views')->groupBy('author')->orderDesc('total_views')->getAll();
		// $result = Demo::select(['author', 'SUM(views) AS total_views'])->groupBy('author')->orderDesc('total_views')->getAll();
		// $result = Demo::select(['author', 'SUM(views) AS total_views'])->groupBy('author')->orderDesc('total_views')->limit(0,3)->getAll();
		// $result = Demo::select(['author', 'SUM(views) AS total_views'])->groupBy('author')->orderDesc('total_views')->limit(1)->getAll();
		// Demo::insert(['article_title' => 'title413', 'content' => 'some content', 'author' => 8, 'views' => 15, 'comments' => 2]);
		// Demo::update(['article_title' => 'title415'])->where(['id' => 415])->set();
		// Demo::update(['article_title' => 'title7', 'content' => 'content 7'])->where(['id' => 7])->set();
		// Demo::update(['article_title' => 'title test', 'content' => 'content test'])->where(['id' => 8, 'article_title' => 'title8'])->set();
		// Demo::update(['article_title' => 'title test', 'content' => 'content test'])->where(['id' => 9])->whereOr(['article_title' => 'title1', 'id' => 12])->set();
		// for ($i=0; $i < 100; $i++) { 
		// 	Demo::insert(['article_title' => "title$i", 'content' => "some content $i", 'author' => ($i%3), 'views' => random_int(1000, 100000), 'comments' => random_int(0, 2000)]);
		// }
		// Demo::delete(415);
		// Demo::delete(['id' => 409, 'author' => 1]);
		// Demo::delete(['author' => 0]);
		$result = Demo::select('*')->orderDesc('id')->limit(5)->getAll();
		echo "<pre>";
		print_r($result);
		echo "</pre>";
		// Demo::initModelProperties();

		// $reference = new Demo();		
		// $articles = $reference->select('*')->get();
		// $articles = $reference->select('*')->getAll();
		// $articles = $reference->select(['id', 'content'])->get();
		// $articles = $reference->select(['id', 'content'])->getAll();
		// $articles = $reference->select('id, content')->get();
		// $articles = $reference->select('id, content')->getAll();

		// echo "<pre>";
		// print_r($articles);
		// echo "</pre>";

		// $reference->insert(['article_title' => 'test fourth title', 'content' => 'some other content']);
		
		return Response::view('demo/show', ['id' => $id, 'test' => 'value'], $responseCode);
	}
}