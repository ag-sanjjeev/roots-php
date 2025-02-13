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
use \Exception;

/**
 * Class Logger
 *
 * Which has properties and methods to log various informations.
 * It has instance, formatter, logDebug, logError, logInfo, logWarning
 * callerDetails, write and rotateLog methods
 *
 */
class Logger
{
	/**
	 * The singleton instance of the Logger class.
	 *
	 * @var Logger
	 */
	public static Logger $instance;

	/**
	 * Constructor for the Logger class.
	 *
	 * Initializes the logger instance.
	 */
	function __construct()
	{
		// Set the singleton instance.
		self::$instance = $this;		
	}

	/**
	 * Returns the singleton instance of the Logger class.
	 *
	 * @return Logger The singleton instance.
	 */
	public static function instance()
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
	 * Formats the log message.
	 *
	 * @param string $timestamp The timestamp of the log message.
	 * @param string $level The log level (e.g., DEBUG, INFO, WARNING, ERROR).
	 * @param string $message The log message.
	 * @param string $file The file where the log message originated.
	 * @param string $line The line number where the log message originated.
	 * @param int|string $processId The process ID.
	 * @param string $context Additional context for the log message.
	 * @return string The formatted log message.
	 */
	private static function formatter(string $timestamp, string $level, string $message, string $file, string $line, int|string $processId, string $context): string 
	{
		// Format the log message using sprintf.
		return $formattedMessage = sprintf("%s %s: [%d] %s: [%s - %s] \n %s", $timestamp, $level, $processId, $message, $file, $line, $context);		
	}

	/**
	 * Logs a debug message.
	 *
	 * @param mixed $object The object or message to log.   
	 * @param mixed $context Additional context for the log message.
	 * @param string $file The file where the log message originated.
	 * @param int|string $line The line number where the log message originated.
	 * @return void
	 */
	public static function logDebug(mixed $object, mixed $context = '', string $file = '', int|string $line = ''): void
	{
		// Get the singleton instance of the logger.
		self::$instance = self::instance();

		// Convert the object to a string.
		$message = (string) $object; 

		// Handle exceptions.
		if ($object instanceof Exception) {
			$message = $object->getMessage();
			$file = $object->getFile();
			$line = $object->getLine();
			$context = empty($context) ? $object->getTraceAsString() : $context;
		}

		// Get file and line if not provided.
		if (empty($file) || empty($line) ) {
			$trace = self::callerDetails();			
			$file = empty($file) ? $trace['file'] : $file;
			$line = empty($line) ? $trace['line'] : $line;
		}

		// Prepare log details.
		$timestamp = date(DATE_ISO8601, time());
		$level = strtoupper('debug');
		$processId = getmypid();
		$logDetails = self::formatter($timestamp, $level, $message, $file, $line, $processId, $context);

		// Write the log message.
		self::write($logDetails, $level);
	}

	/**
	 * Logs an error message.
	 *
	 * @param mixed $object The object or message to log.
	 * @param mixed $context Additional context for the log message.
	 * @param string $file The file where the log message originated.
	 * @param int|string $line The line number where the log message originated.
	 * @return void
	 */
	public static function logError(mixed $object, mixed $context = '', string $file = '', int|string $line = ''): void
	{
		// Get the singleton instance of the logger.
		self::$instance = self::instance();

		// Convert the object to a string.
		$message = (string) $object; 

		// Handle exceptions.
		if ($object instanceof Exception) {
			$message = $object->getMessage();
			$file = $object->getFile();
			$line = $object->getLine();
			$context = $object->getTraceAsString();
		}

		// Get file and line if not provided.
		if (empty($file) || empty($line) ) {
			$trace = self::callerDetails();			
			$file = empty($file) ? $trace['file'] : $file;
			$line = empty($line) ? $trace['line'] : $line;
		}

		// Prepare log details.
		$timestamp = date(DATE_ISO8601, time());
		$level = strtoupper('error');
		$processId = getmypid();
		$logDetails = self::formatter($timestamp, $level, $message, $file, $line, $processId, $context);

		// Write the log message.
		self::write($logDetails, $level);
	}

	/**
	 * Logs an informational message.
	 *
	 * @param string $object The message to log.
	 * @param mixed $context Additional context for the log message.
	 * @param string $file The file where the log message originated.
	 * @param int|string $line The line number where the log message originated.
	 * @return void
	 */
	public static function logInfo(string $object, mixed $context = '', string $file = '', int|string $line = ''): void
	{
		// Get the singleton instance of the logger.
		self::$instance = self::instance();
		
		$message = $object; 	

		// Get file and line if not provided.	
		if (empty($file) || empty($line) ) {
			$trace = self::callerDetails();			
			$file = empty($file) ? $trace['file'] : $file;
			$line = empty($line) ? $trace['line'] : $line;
		}

		// Prepare log details.
		$timestamp = date(DATE_ISO8601, time());
		$level = strtoupper('info');
		$processId = getmypid();
		$logDetails = self::formatter($timestamp, $level, $message, $file, $line, $processId, $context);

		// Write the log message.
		self::write($logDetails, $level);
	}

	/**
	 * Logs a warning message.
	 *
	 * @param mixed $object The object or message to log. 
	 * @param mixed $context Additional context for the log message.
	 * @param string $file The file where the log message originated.
	 * @param int|string $line The line number where the log message originated.
	 * @return void
	 */
	public static function logWarning(mixed $object, mixed $context = '', string $file = '', int|string $line = ''): void
	{
		// Get the singleton instance of the logger.
		self::$instance = self::instance();

		// Convert the object to a string.
		$message = (string) $object; 

		// Handle exceptions.
		if ($object instanceof Exception) {
			$message = $object->getMessage();
			$file = $object->getFile();
			$line = $object->getLine();
		}

		// Get file and line if not provided.
		if (empty($file) || empty($line) ) {
			$trace = self::callerDetails();			
			$file = empty($file) ? $trace['file'] : $file;
			$line = empty($line) ? $trace['line'] : $line;
		}

		// Prepare log details.
		$timestamp = date(DATE_ISO8601, time());
		$level = strtoupper('warning');
		$processId = getmypid();
		$logDetails = self::formatter($timestamp, $level, $message, $file, $line, $processId, $context);

		// Write the log message.
		self::write($logDetails, $level);
	}

	/**
	 * Retrieves the file and line number of the calling function.
	 *
	 * @return array An associative array containing the 'file' and 'line' of the caller.
	 */
	private static function callerDetails()
	{
		// Get debug backtrace information, limiting to 2 stack frames.
		$traceDetails = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2); 

		// Extract file and line information from the backtrace.
		$file = $traceDetails[1]['file'] ?? 'Unknown File';
		$line = $traceDetails[1]['line'] ?? 'Unknown Line';
		
		return ['file' => $file, 'line' => $line];
	}

	/**
	 * Writes the log message to the appropriate log file.
	 *
	 * @param string $details The formatted log message.
	 * @param string $level The log level (e.g., DEBUG, INFO, WARNING, ERROR).
	 * @return void
	 */
	private static function write(string $details, string $level): void
	{
		// Retrieve configuration values.
		$root_path = Configuration::get('application.root_path');
		$logDirectory = Configuration::get('logger.path');
		$logDirectory = $root_path . DIRECTORY_SEPARATOR . $logDirectory;
		$maxLogFiles = Configuration::get('logger.max_files');
		$fileSize = Configuration::get('logger.file_size');

		// Create the log directory if it doesn't exist.
		if (!is_dir($logDirectory)) {
			if (!mkdir($logDirectory, 0777, true)) { // 0777 permissions for recursive creation
				echo "Failed to create log directory for the $level";
				die();
			}
		}

		// Determine the log file name.
		$fileName = $logDirectory . DIRECTORY_SEPARATOR . strtolower($level) . ".log";

		// Rotate the log file if necessary.
		self::rotateLog($fileName, $level, $fileSize, $maxLogFiles);

		// Write the log message to the file.
		if (file_put_contents($fileName, $details, FILE_APPEND | LOCK_EX) === false) {
			echo "Unable to write log";
			die();
		}

	}

	/**
	 * Rotates the log file if it exceeds the specified file size limit.
	 *
	 * @param string $fileName The path to the log file.
	 * @param string $level The log level.
	 * @param int|string $fileSize The maximum log file size in bytes.
	 * @param int|string $maxLogFiles The maximum number of log files to keep.
	 * @return void
	 */
	private static function rotateLog(string $fileName, string $level, int|string $fileSize, int|string $maxLogFiles): void
	{
		// Check if the log file exists and its size exceeds the limit.
		if (file_exists($fileName) && filesize($fileName) >= $fileSize) {
			// Create a backup of the current log file by renaming it with a timestamp.
			$backupLog = dirname($fileName) . DIRECTORY_SEPARATOR . strtolower($level) . "." . date("YmdHis") . ".log";
			rename($fileName, $backupLog);			

			// Delete old log files if the number of backups exceeds the limit.
			$directoryName = dirname($backupLog);
			$logFiles = glob($directoryName . DIRECTORY_SEPARATOR . strtolower($level) . "*.log");

			if ($logFiles === false) {
				echo "Error in log directory";
				die();
			} else if (!empty($logFiles) && count($logFiles) > $maxLogFiles) {				
				asort($logFiles); // Sort log files by name (ascending).
				array_splice($logFiles, -($maxLogFiles)); // Keep only the most recent $maxLogFiles files.
				foreach ($logFiles as $file) {
					unlink($directoryName . DIRECTORY_SEPARATOR . basename($file)); // Delete the oldest log files.
				}
			}
		}
	}
}