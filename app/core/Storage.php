<?php

namespace roots\app\core;

use roots\app\core\Configuration;
use roots\app\core\Request;
use roots\app\core\Session;
use roots\app\core\ExceptionHandler;
use roots\app\core\Logger;
use \SplFileInfo;
use \Exception;

/**
 * Storage Class
 */
class Storage
{
	// public static Storage class instance property
	public static Storage $instance;

	// private static default storage path if it not configured
	private static string $defaultStorageDirectory = 'storage';

	// private static actual storage directory property
	private static string $storageDirectory;

	// private static actual storage path property
	private static string $storagePath;

	// private static root path property
	private static string $rootPath;

	// private static host address property
	private static string $host;

	// private static base URL address property
	private static string $baseURL;

	// private static virtual path property
	private static string $virtualPath;

	// Initiate Storage
	function __construct()
	{
		self::$instance = $this;
		self::$rootPath = Configuration::get('application.root_path');
		self::$storageDirectory = Configuration::get('application.storage_directory');		
		self::$baseURL = Request::baseURL();
		self::$host = Request::host();

		if (is_null(self::$rootPath) || empty(self::$rootPath)) {
			throw new Exception("Root Path Missing", 1);
			die();
		}

		if (is_null(self::$storageDirectory) || empty(self::$storageDirectory)) {
			Logger::logWarning('Missing storage directory and setting default storage directory');
			self::$storageDirectory = self::$defaultStorageDirectory;
		}

		self::$storagePath = self::$rootPath . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . self::$storageDirectory;

		// Creating a storage directory if it was not created yet
		if (!is_dir(self::$storagePath)) {
			if (!mkdir(self::$storagePath)) {
				throw new Exception("Cannot create storage directory", 1);
				die();
			} else {
				Logger::logDebug("Storage directory created");
			}
		}
	}

	// public static instance method
	public static function instance(): object
	{
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	// public static method for storage path
	public static function path(string $fileTarget): bool|string
	{
		self::$instance = self::instance();

		if (empty($fileTarget)) {
			throw new Exception("File target cannot be an empty", 1);
			return false;
		}

		$fileTarget = self::$storagePath . DIRECTORY_SEPARATOR . trim($fileTarget, DIRECTORY_SEPARATOR);

		if (!file_exists($fileTarget)) {
			throw new Exception("File is not exist", 1);
			return false;
		}

		return $fileTarget;
	}

	// public static method for file path
	public static function filePath(string $fileTarget): bool|string
	{
		self::$instance = self::instance();

		if (empty($fileTarget)) {
			throw new Exception("File target cannot be an empty", 1);
			return false;
		}
		$fileTarget = trim($fileTarget, DIRECTORY_SEPARATOR);
		$filePath = self::$storagePath . DIRECTORY_SEPARATOR . $fileTarget;

		if (!file_exists($filePath)) {
			throw new Exception("File is not exist", 1);
			return false;
		}

		$fileTarget = self::$baseURL . DIRECTORY_SEPARATOR . self::$storageDirectory . DIRECTORY_SEPARATOR . $fileTarget;

		return $fileTarget;
	}

	// public static method for file size
	public static function fileSize(string $fileTarget): mixed
	{
		self::$instance = self::instance();

		$path = self::path($fileTarget);

		if ($path !== false) {			
			return filesize($path);	
		}
		return false;
	}

	// public static method for file upload
	public static function upload(array $file, string $target): bool
	{
		self::$instance = self::instance();		
		if (!is_array($file)) {
			throw new Exception("File is not uploaded", 1);
			return false;
		}

		if (empty($target)) {
			throw new Exception("Cannot upload to the empty target", 1);
			return false;
		}

		$target = trim(trim($target, '\\'), '/');

		// adding file extension based on uploaded
		$uploadedFileName = explode('.', $file['name']);
		$extension = array_pop($uploadedFileName);
		$target .= '.' . $extension;

		$target = self::$storagePath . DIRECTORY_SEPARATOR . $target;

		if (file_exists($target)) {
			throw new Exception("Uploaded file name already exist", 1);
			return false;
		}

		return move_uploaded_file($file['tmp_name'], $target);
	}

	// public static method for file upload as 
	public static function uploadAs(array $file, string $target, string $fileName): bool
	{
		self::$instance = self::instance();

		if (empty($target)) {
			throw new Exception("Cannot upload to the empty target", 1);
			return false;
		}

		if (empty($fileName)) {
			throw new Exception("Upload file name cannot be an empty", 1);
			return false;
		}

		if (strpos($fileName, '/') !== false || strpos($fileName, '\\' !== false)) {
			throw new Exception("File name does not allowed with path", 1);
			return false;
		}

		$target = trim(trim($target, '\\'), '/');

		$targetPath = self::$storagePath . DIRECTORY_SEPARATOR . $target;

		if (!is_dir($targetPath)) {
			if (!mkdir($targetPath, 0777, true)) {
				throw new Exception("Cannot create target path directories", 1);
				return false;
			}
		}

		$targetPath = $target . DIRECTORY_SEPARATOR . trim($fileName);

		return self::upload($file, $targetPath);
	}

	// public static method for file download
	public static function download(string $file): mixed
	{
		self::$instance = self::instance();

		if (empty($file)) {
			throw new Exception("File name cannot be an empty", 1);
			return false;
		}

		$file = trim(trim(trim($file), '/'), '\\');		
		$targetPath = self::path($file);

		if (!file_exists($targetPath) || !is_readable($targetPath)) {
			throw new Exception("File is neither exist nor readable", 1);
			return false;
		}

		$target = self::filePath($file);

		$mimeType = mime_content_type($targetPath);
		$mimeType = $mimeType ? $mimeType : 'application/octet-stream';
		$targetSize = self::fileSize($file);
		$filename = basename($targetPath);

		if (!headers_sent()) {
			header('Content-Type:' . $mimeType);
			header('Content-Disposition: attachment; filename="' . $filename . '"');
			header('Content-Description: File Transfer');
			header("Content-Transfer-Encoding: binary");
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: no-cache');
			header('Content-Length: ' . $targetSize);
			flush();
			readfile($targetPath);
			exit;			
		}
	}

	// public static method for unlink file
	public static function unlink(string $link): bool
	{
		self::$instance = self::instance();

		if (empty($link)) {
			throw new Exception("Cannot unlink file for empty target", 1);
			return false;
		}

		$link = trim(trim(trim($link), '/'), '\\');
		$link = self::$storagePath . DIRECTORY_SEPARATOR . $link;

		if (!file_exists($link)) {
			throw new Exception("Cannot unlink not exist file", 1);
			return false;
		}

		return unlink($link);
	}
}