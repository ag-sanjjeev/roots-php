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
use roots\app\core\Logger;
use \PDO;
use \Exception;

/**
 * Class Database
 *
 * Which has properties and methods to handle database.
 * It has instance, prepareDSN, connect, startTransaction, commitTransaction, 
 * rollBackTransaction and extended PDO methods
 *
 */
class Database extends PDO
{
	/**
	 * The singleton instance of the Database class.
	 *
	 * @var Database
	 */
	private static Database $instance;

	/**
	 * The Data Source Name (DSN) for the database connection.
	 *
	 * @var string
	 */
	private string $dsn;

	/**
	 * The database driver.
	 *
	 * @var string
	 */
	private string $driver;

	/**
	 * The hostname or IP address of the database server.
	 *
	 * @var string
	 */
	private string $host;

	/**
	 * The port number for the database connection.
	 *
	 * @var string
	 */
	private string $port;

	/**
	 * The name of the database.
	 *
	 * @var string
	 */
	private string $dbname;

	/**
	 * The username for the database connection.
	 *
	 * @var string
	 */
	private string $dbusername;

	/**
	 * The password for the database connection.
	 *
	 * @var string
	 */
	private string $dbpassword;

	/**
	 * Constructor for the Database class.
	 *
	 * Initializes the database connection.
	 */
	public function __construct()
	{
		// Set the singleton instance to the current object.
		self::$instance = $this;
		// Establish the database connection.
		$this->connect();
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
			self::$instance = new self;
		}

		// Return the singleton instance.
		return self::$instance;
	}

	/**
	 * Prepares the Data Source Name (DSN) string for the database connection.
	 *
	 * @return void
	 * @throws Exception If an unsupported database driver is specified.
	 */
	private function prepareDSN():void
	{
		// Retrieve database configuration values.
		$this->driver = Configuration::get('database.driver');
		$this->host = Configuration::get('database.host');
		$this->port = Configuration::get('database.port');
		$this->dbname = Configuration::get('database.dbname');
		$this->dbusername = Configuration::get('database.username');
		$this->dbpassword = Configuration::get('database.password');

		// Get the list of available PDO drivers.
		$availableDriver = parent::getAvailableDrivers();

		// Check if the specified driver is supported.
		if (!in_array($this->driver, $availableDriver)) {
			// Throw an exception if the driver is not supported.
			throw new Exception("Unsupported Database Driver '$this->driver'", 1);			
		}		

		// Construct the DSN string.
		$this->dsn = $this->driver . ":host=" . $this->host . ":" . $this->port . ";dbname=" . $this->dbname;
	}

	/**
	 * Establishes a connection to the database.
	 *
	 * @return void
	 * @throws PDOException If a database connection error occurs.
	 */
	public function connect():void
	{
		try {
			// Prepare the DSN string.
			$this->prepareDSN();
			// Call the parent constructor to establish the PDO connection.
			parent::__construct($this->dsn, $this->dbusername, $this->dbpassword);
		} catch (PDOException $e) {
			// Handle database connection errors.
			echo "Database Connection Failed" . PHP_EOL . $e->getMessage();
			die();
		}
	}

	/**
	 * Starts a database transaction.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function startTransaction():bool
	{
		// Begin the database transaction.
		return parent::beginTransaction();
	}

	/**
	 * Commits the current database transaction.
	 *
	 * @return bool True on success, false on failure 
	 */
	public function commitTransaction():bool
	{
		// Check if a transaction is currently active.
		if (parent::inTransaction()) {
			// Commit the transaction.
			return parent::commit();
		}
		// Return false if no transaction was active.
		return false;
	}

	/**
	 * Rolls back the current database transaction.
	 *
	 * @return bool True on success, false on failure
	 */
	public function rollBackTransaction():bool
	{
		// Check if a transaction is currently active.
		if (parent::inTransaction()) {
			// Roll back the transaction.
			return parent::rollBack();
		}
		// Return false if no transaction was active.
		return false;
	}

}