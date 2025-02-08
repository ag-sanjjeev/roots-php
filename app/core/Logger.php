<?php

namespace roots\app\core;

use roots\app\core\Configuration;
use \Exception;

/**
 * Logger Class
 */
class Logger
{
	// public static Logger instance property
	public static Logger $instance;

	function __construct()
	{
		self::$instance = $this;		
	}

	public static function instance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private static function formatter(string $timestamp, string $level, string $message, string $file, string $line, int|string $processId, string $context): string 
	{
		return $formattedMessage = sprintf("%s %s: [%d] %s: [%s - %s] \n %s", $timestamp, $level, $processId, $message, $file, $line, $context);		
	}

	public static function logDebug(mixed $object, mixed $context = '', string $file = '', int|string $line = ''): void
	{
		self::$instance = self::instance();
		$message = (string) $object; 
		if ($object instanceof Exception) {
			$message = $object->getMessage();
			$file = $object->getFile();
			$line = $object->getLine();
			$context = empty($context) ? $object->getTraceAsString() : $context;
		}
		if (empty($file) || empty($line) ) {
			$trace = self::callerDetails();			
			$file = empty($file) ? $trace['file'] : $file;
			$line = empty($line) ? $trace['line'] : $line;
		}
		$timestamp = date(DATE_ISO8601, time());
		$level = strtoupper('debug');
		$processId = getmypid();
		$logDetails = self::formatter($timestamp, $level, $message, $file, $line, $processId, $context);
		self::write($logDetails, $level);
	}

	public static function logError(mixed $object, mixed $context = '', string $file = '', int|string $line = ''): void
	{
		self::$instance = self::instance();
		$message = (string) $object; 
		if ($object instanceof Exception) {
			$message = $object->getMessage();
			$file = $object->getFile();
			$line = $object->getLine();
			$context = $object->getTraceAsString();
		}
		if (empty($file) || empty($line) ) {
			$trace = self::callerDetails();			
			$file = empty($file) ? $trace['file'] : $file;
			$line = empty($line) ? $trace['line'] : $line;
		}
		$timestamp = date(DATE_ISO8601, time());
		$level = strtoupper('error');
		$processId = getmypid();
		$logDetails = self::formatter($timestamp, $level, $message, $file, $line, $processId, $context);
		self::write($logDetails, $level);
	}

	public static function logInfo(string $object, mixed $context = '', string $file = '', int|string $line = ''): void
	{
		self::$instance = self::instance();
		$message = $object; 		
		if (empty($file) || empty($line) ) {
			$trace = self::callerDetails();			
			$file = empty($file) ? $trace['file'] : $file;
			$line = empty($line) ? $trace['line'] : $line;
		}
		$timestamp = date(DATE_ISO8601, time());
		$level = strtoupper('info');
		$processId = getmypid();
		$logDetails = self::formatter($timestamp, $level, $message, $file, $line, $processId, $context);
		self::write($logDetails, $level);
	}

	public static function logWarning(mixed $object, mixed $context = '', string $file = '', int|string $line = ''): void
	{
		self::$instance = self::instance();
		$message = (string) $object; 
		if ($object instanceof Exception) {
			$message = $object->getMessage();
			$file = $object->getFile();
			$line = $object->getLine();
		}
		if (empty($file) || empty($line) ) {
			$trace = self::callerDetails();			
			$file = empty($file) ? $trace['file'] : $file;
			$line = empty($line) ? $trace['line'] : $line;
		}
		$timestamp = date(DATE_ISO8601, time());
		$level = strtoupper('warning');
		$processId = getmypid();
		$logDetails = self::formatter($timestamp, $level, $message, $file, $line, $processId, $context);
		self::write($logDetails, $level);
	}

	private static function callerDetails()
	{
		$traceDetails = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2); // limit to 2 frame level	
		$file = $traceDetails[1]['file'] ?? 'Unknown File';
		$line = $traceDetails[1]['line'] ?? 'Unknown Line';
		
		return ['file' => $file, 'line' => $line];
	}

	private static function write(string $details, string $level): void
	{
		$root_path = Configuration::get('application.root_path');
		$logDirectory = Configuration::get('logger.path');
		$logDirectory = $root_path . DIRECTORY_SEPARATOR . $logDirectory;
		$maxLogFiles = Configuration::get('logger.max_files');
		$fileSize = Configuration::get('logger.file_size');

		// Create Directory if not created yet
		if (!is_dir($logDirectory)) {
			if (!mkdir($logDirectory, 0777, true)) { // 0777 permissions for recursive creation
				echo "Failed to create log directory for the $level";
				die();
			}
		}

		// Filename
		$fileName = $logDirectory . DIRECTORY_SEPARATOR . strtolower($level) . ".log";

		// Rotate Log
		self::rotateLog($fileName, $level, $fileSize, $maxLogFiles);

		// Create if file not exist then write to file
		if (file_put_contents($fileName, $details, FILE_APPEND | LOCK_EX) === false) {
			echo "Unable to write log";
			die();
		}

	}

	private static function rotateLog(string $fileName, string $level, int|string $fileSize, int|string $maxLogFiles): void
	{
		// Check log file exists and size above the allowed limit
		if (file_exists($fileName) && filesize($fileName) >= $fileSize) {
			// backup current file by rename
			$backupLog = dirname($fileName) . DIRECTORY_SEPARATOR . strtolower($level) . "." . date("YmdHis") . ".log";
			rename($fileName, $backupLog);			

			// Delete old log file by maximum preserve backup count
			$directoryName = dirname($backupLog);
			$logFiles = glob($directoryName . DIRECTORY_SEPARATOR . strtolower($level) . "*.log");

			if ($logFiles === false) {
				echo "Error in log directory";
				die();
			} else if (!empty($logFiles) && count($logFiles) > $maxLogFiles) {				
				asort($logFiles); // order files in ascending 
				array_splice($logFiles, -($maxLogFiles)); // take first file with maxLogFiles as offset
				foreach ($logFiles as $file) {
					unlink($directoryName . DIRECTORY_SEPARATOR . basename($file)); // remove old log files	
				}
			}
		}
	}
}