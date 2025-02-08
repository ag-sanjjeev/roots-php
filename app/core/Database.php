<?php

namespace roots\app\core;

use roots\app\core\Configuration;
use roots\app\core\Logger;
use \PDO;
use \Exception;

/**
 * Database class
 */
class Database extends PDO
{
	// Database instance private static property
	private static Database $instance;

	// Data Source Name private property
	private string $dsn;

	// Database driver private property
	private string $driver;

	// Host private property
	private string $host;

	// Port private property
	private string $port;

	// DB name private property
	private string $dbname;

	// DB username private property
	private string $dbusername;

	// DB password private property
	private string $dbpassword;

	public function __construct()
	{
		self::$instance = $this;
		$this->connect();
	}

	public static function instance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	private function prepareDSN():void
	{

		// Prepare $dsn property
		$this->driver = Configuration::get('database.driver');
		$this->host = Configuration::get('database.host');
		$this->port = Configuration::get('database.port');
		$this->dbname = Configuration::get('database.dbname');
		$this->dbusername = Configuration::get('database.username');
		$this->dbpassword = Configuration::get('database.password');

		$availableDriver = parent::getAvailableDrivers();

		if (!in_array($this->driver, $availableDriver)) {
			throw new Exception("Unsupported Database Driver '$this->driver'", 1);
			die();
		}		

		$this->dsn = $this->driver . ":host=" . $this->host . ":" . $this->port . ";dbname=" . $this->dbname;
	}


	public function connect():void
	{
		try {
			$this->prepareDSN();
			parent::__construct($this->dsn, $this->dbusername, $this->dbpassword);
		} catch (PDOException $e) {
			echo "Database Connection Failed" . PHP_EOL . $e->getMessage();
			die();
		}
	}

	public function startTransaction():bool
	{
		return parent::beginTransaction();
	}

	public function commitTransaction():bool
	{
		if (parent::inTransaction()) {
			return parent::commit();
		}
		return false;
	}
	public function rollBackTransaction():bool
	{
		if (parent::inTransaction()) {
			return parent::rollBack();
		}
		return false;
	}

}