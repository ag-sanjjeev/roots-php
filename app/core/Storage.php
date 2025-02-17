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

namespace roots\app\core;

use roots\app\core\Configuration;
use roots\app\core\Request;
use roots\app\core\Session;
use roots\app\core\ExceptionHandler;
use roots\app\core\Logger;
use \SplFileInfo;
use \Exception;

/**
 * Class Storage
 *
 * Which has properties and methods to handle storage.
 * It has instance, path, filePath, fileSize, upload, 
 * uploadAs, download and unlink methods
 *
 */
class Storage
{
	/**
	 * The singleton instance of the Storage class.
	 *
	 * @var Storage|null
	 */
	public static ?Storage $instance = null;

	/**
	 * The default storage directory for files.
	 *
	 * This path is relative to the application's root directory.
	 *
	 * @var string
	 */
	private static string $defaultStorageDirectory = 'storage';

	/**
	 * The configured storage directory for files.
	 *
	 * This path is relative to the application's root directory.  If not explicitly
	 * configured, the default storage directory (`self::$defaultStorageDirectory`) is used.
	 *
	 * @var string
	 */
	private static string $storageDirectory;

	/**
	 * The actual, absolute and full path to the storage directory.
	 *
	 * This path is calculated by combining the application's root path with the
	 * configured storage directory (`self::$storageDirectory`).
	 *
	 * @var string
	 */
	private static string $storagePath;

	/**
	 * The root path of the application.
	 *
	 * This property stores the absolute path to the application's root directory.
	 * It is typically used as a base for constructing other paths within the application.
	 *
	 * @var string
	 */
	private static string $rootPath;

	/**
	 * The host name or IP address of the server.
	 *
	 * @var string
	 */
	private static string $host;

	/**
	 * The base URL of the application (including protocol and host).
	 *
	 * @var string
	 */
	private static string $baseURL;

	/**
	 * Constructor for the Storage class.
	 *
	 * Initializes the Storage instance, root path, storage directory, 
	 * base URL, and host.
	 * Creates the storage directory if it doesn't exist.
	 *
	 * @throws Exception If the root path cannot be determined 
	 * Or the storage directory cannot be created.
	 */
	function __construct()
	{
		self::$instance = $this;
		self::$rootPath = Configuration::get('application.root_path');
		self::$storageDirectory = Configuration::get('application.storage_directory');		
		self::$baseURL = Request::baseURL();
		self::$host = Request::host();

		if (is_null(self::$rootPath) || empty(self::$rootPath)) {
			throw new Exception("Root Path Missing", 1);
		}

		if (is_null(self::$storageDirectory) || empty(self::$storageDirectory)) {
			Logger::logWarning('Missing storage directory and setting default storage directory');
			self::$storageDirectory = self::$defaultStorageDirectory;
		}

		self::$storagePath = self::$rootPath . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . self::$storageDirectory;

		// Create storage directory if it doesn't exist.
		if (!is_dir(self::$storagePath)) {
			if (!mkdir(self::$storagePath)) {
				throw new Exception("Cannot create storage directory", 1);
			} else {
				Logger::logDebug("Storage directory created");
			}
		}
	}

	/**
	 * Returns the singleton instance of the Database class.
	 *
	 * @return Database The singleton instance.
	 */
	public static function instance(): object
	{
		// Check if an instance already exists.
		if (!isset(self::$instance)) {
			// Create a new instance if one doesn't exist.
			self::$instance = new self();
		}

		// Return the singleton instance.
		return self::$instance;
	}

	/**
	 * Returns the full path to a file within the storage directory.
	 *
	 * @param string $fileTarget The file target path, relative to the storage directory.
	 * @return string|false The full path to the file, 
	 * Or false if the file does not exist or the target is empty.
	 * @throws Exception If the file target is empty.
	 */
	public static function path(string $fileTarget): bool|string
	{
		// Get storage instance
		self::$instance = self::instance();

		// Check if the fileTarget is missing
		if (empty($fileTarget)) {
			throw new Exception("File target cannot be an empty", 1);
		}

		$fileTarget = self::$storagePath . DIRECTORY_SEPARATOR . trim($fileTarget, DIRECTORY_SEPARATOR);

		if (!file_exists($fileTarget)) {
			return false;
		}

		return $fileTarget;
	}

	/**
	 * Returns the full URL to a file within the storage directory.
	 *
	 * @param string $fileTarget The file target path, relative to the storage directory.
	 * @return string|false The full URL to the file, 
	 * Or false if the file does not exist or the target is empty.
	 * @throws Exception If the file target is empty.
	 */
	public static function filePath(string $fileTarget): bool|string
	{
		// Get storage instance
		self::$instance = self::instance();

		// Check if the fileTarget is empty
		if (empty($fileTarget)) {
			throw new Exception("File target cannot be an empty", 1);
		}

		$fileTarget = trim($fileTarget, DIRECTORY_SEPARATOR);
		$filePath = self::$storagePath . DIRECTORY_SEPARATOR . $fileTarget;

		if (!file_exists($filePath)) {
			return false;
		}

		$fileTarget = self::$baseURL . DIRECTORY_SEPARATOR . self::$storageDirectory . DIRECTORY_SEPARATOR . $fileTarget;

		return $fileTarget;
	}

	/**
	 * Returns the size of a file within the storage directory.
	 *
	 * @param string $fileTarget The file target path, relative to the storage directory.
	 * @return int|false The size of the file in bytes, or false if the file does not exist.
	 */
	public static function fileSize(string $fileTarget): mixed
	{
		// Get storage instance
		self::$instance = self::instance();

		// Get file path for given target
		$path = self::path($fileTarget);

		// Returns files size if exist
		if ($path !== false) {			
			return filesize($path);	
		}

		// Return false if the file is not exist
		return false;
	}

	/**
	 * Uploads a file to the storage directory.
	 *
	 * @param array $file An associative array containing file information (typically from $_FILES).
	 * @param string $target The target path relative to the storage directory 
	 * with file name (e.g., 'images/filename.jpg').
	 * @return bool True on successful upload, false otherwise.
	 * @throws Exception If the file array is invalid, the target is empty, 
	 * Or a file with the same name already exists.
	 */
	public static function upload(array $file, string $target): bool
	{
		// Get storage instance
		self::$instance = self::instance();		

		// Check for valid file array
		if (!is_array($file) || !isset($file['tmp_name'], $file['name'], $file['error'])) {
			throw new Exception("Invalid file array provided", 1);
		}

		if ($file['error'] !== UPLOAD_ERR_OK) { // Check for upload errors.
        throw new Exception("File upload failed with error code: " . $file['error'], 1);
    }

		if (empty($target)) { // Check for empty target
			throw new Exception("Target directory cannot be empty.", 1);
		}

		$target = trim(trim($target, '\\'), '/');

		// Adding file extension based on uploaded
		$uploadedFileName = explode('.', $file['name']);
		$extension = array_pop($uploadedFileName);
		$target .= '.' . $extension;

		$targetPath = self::$storagePath . DIRECTORY_SEPARATOR . $target;

		if (file_exists($targetPath)) {
			throw new Exception("Uploaded file name already exist" . $targetPath, 1);
		}

		if (!move_uploaded_file($file['tmp_name'], $targetPath)) { // upload file to target
        throw new Exception("File upload failed. Could not move uploaded file.", 1); 
    }

    return true;
	}

	/**
	 * Uploads a file to the storage directory with a specified file name.
	 *
	 * @param array $file An associative array containing file information (typically from $_FILES).
	 * @param string $target The target directory relative to the storage directory (e.g., 'images/').
	 * @param string $fileName The desired file name (without path components).
	 * @return bool True on successful upload, false otherwise.
	 * @throws Exception If the target or file name is empty, the file name contains path
	 * separators, or the target directory cannot be created.
	 */
	public static function uploadAs(array $file, string $target, string $fileName): bool
	{
		// Get storage instance
		self::$instance = self::instance();

		if (!is_array($file) || !isset($file['tmp_name'], $file['name'], $file['error'])) { // Check for valid file array.
        throw new Exception("Invalid file array provided.", 1);
    }

    if ($file['error'] !== UPLOAD_ERR_OK) { // Check for upload errors.
        throw new Exception("File upload failed with error code: " . $file['error'], 1); 
    }

		if (empty($target)) {	// Check target is an empty
			throw new Exception("Cannot upload to the empty target", 1);
			return false;
		}

		if (empty($fileName)) { // Check filename is an empty
			throw new Exception("Upload file name cannot be an empty", 1);
			return false;
		}

		if (strpos($fileName, '/') !== false || strpos($fileName, '\\' !== false)) {
			throw new Exception("File name cannot contain path separators", 1);
		}

		$target = trim(trim($target, '\\'), '/');

		$targetPath = self::$storagePath . DIRECTORY_SEPARATOR . $target;

		if (!is_dir($targetPath)) {
			if (!mkdir($targetPath, 0777, true)) {
				throw new Exception("Cannot create target directory", 1);
			}
		}

		// Adding file extension based on uploaded
		$uploadedFileName = explode('.', $file['name']);
		$extension = array_pop($uploadedFileName);		

		$targetPath .= DIRECTORY_SEPARATOR . trim($fileName) . '.' . $extension;

		if (file_exists($targetPath)) { // File is already exist
        throw new Exception("File with that name already exists: " . $targetPath, 1); 
    }


    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        throw new Exception("File upload failed. Could not move uploaded file.", 1);
    }

		return true;
	}

	/**
	 * Downloads a file from the storage directory.
	 *
	 * @param string $file The path to the file relative to the storage directory.
	 * @return void|false  If headers are already sent, returns false. 
	 * Otherwise, sends the file for download and exits the script.
	 * @throws Exception If the file name is empty or the file is not found or readable.
	 */
	public static function download(string $file): mixed
	{
		// Get storage instance
		self::$instance = self::instance();

		if (empty($file)) { // Check for file target is an empty 
			throw new Exception("File name cannot be an empty", 1);
		}

		$file = trim(trim(trim($file), '/'), '\\');		
		$targetPath = self::path($file);

		if ($targetPath === false || !is_readable($targetPath)) { // Check for file exist and readable
			throw new Exception("File is neither exist nor readable", 1);
		}

		$target = self::filePath($file);

		$mimeType = mime_content_type($targetPath);
		$mimeType = $mimeType ? $mimeType : 'application/octet-stream';
		$targetSize = self::fileSize($file);
		$filename = basename($targetPath);

		if (headers_sent()) {
			return false; // Return false if headers are already sent
		}

		header('Content-Type:' . $mimeType);
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Content-Description: File Transfer');
		header("Content-Transfer-Encoding: binary");
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . $targetSize);
		flush();
		readfile($targetPath);
		exit;					
	}

	/**
	 * Unlinks (deletes) a file from the storage directory.
	 *
	 * @param string $link The path to the file relative to the storage directory.
	 * @return bool True on successful deletion, false otherwise.
	 * @throws Exception If the link is empty or the file does not exist.
	 */
	public static function unlink(string $link): bool
	{
		// Get storage instance
		self::$instance = self::instance();

		if (empty($link)) { // Check for empty link
			throw new Exception("File path cannot be an empty", 1);
		}

		$link = trim(trim(trim($link), '/'), '\\');
		$link = self::$storagePath . DIRECTORY_SEPARATOR . $link;

		if (!file_exists($link)) {
			return false;
		}

		return unlink($link);
	}
}