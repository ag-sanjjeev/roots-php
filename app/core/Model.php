<?php

namespace roots\app\core;

use roots\app\core\Database;
use roots\app\core\Logger;
use \PDO;
use \PDOStatement;
use \Exception;

/**
 * Model class
 */
class Model
{

	// Model instance private static property
	private static Model|null $instance;

	// Database instance protected property
	protected static Database $db; 

	// Dynamic protected array property
	protected array $d = [];

	// tableName protected static property
	protected static string $tableName = '';

	// fields protected static array property
	protected static array $fields = [];

	// default primaryKey protected static string property
	protected static string $primaryKey = '';

	// query private static property
	private static string $query =  '';

	// query params private static property
	private static array $queryParams;

	// query params Counter private static property
	private static array $queryParamsCounter;

	// lastStatement private static property
	private static string $lastStatement;

	// query statement private static property
	private static PDOStatement $statement;

	// isAnyException private static property
	private static bool $isAnyException;

	// queryExecuted private static property
	private static bool $queryExecuted;

	public function __construct()
	{
		self::$instance = $this;
		self::$db = Database::instance();
		self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		self::initModelProperties();		
		self::$queryParams = [];
		self::$queryParamsCounter = []; // count for individual query params to avoid conflicts
		self::$isAnyException = false;
		self::$queryExecuted = false;
		self::$lastStatement = '';		
	}

	public static function instance(): object
	{
		if (!isset(self::$instance)) {
			self::$instance = new static();
		}

		return self::$instance;
	}

	private static function resetInstance(): void
	{
		self::$instance = null;
	}

	public function _set(int|string $key, mixed $value = ''):	void
	{
		$this->d[$key] = $value;
	}

	public function _get(int|string $key): mixed
	{
		return $this->d[$key] ?? null;
	}

	public static function initModelProperties(): void
	{		
		if (isset(static::$tableName) && !empty(static::$tableName)) {
			self::$tableName = static::$tableName;
		} else {
			self::$tableName = self::getTable();
		}

		if (isset(static::$fields) && !empty(static::$fields)) {
			self::$fields = static::$fields;
		} else {
			self::$fields = self::getFields();
		}

		if (empty(self::$tableName)) {
			self::$isAnyException = true;
			throw new Exception("Model Table Name Not Specified Error", 1);
			die();
		}

		if (empty(self::$fields)) {	
			self::$isAnyException = true;		
			throw new Exception("Model Fields Not Specified Error", 1);
			die();
		}
	}

	private static function getTable(): string
	{
		$tableName = get_called_class();
		$tableName = explode("\\", $tableName);
		$tableName = array_pop($tableName);
	
		return $tableName;
	}

	private static function getFields(): array
	{
		$fields = [];
		$query = "SHOW COLUMNS FROM " . self::$tableName;		
		$statement = self::$db->prepare($query);
		if ($statement->execute()) {
			$fields = $statement->fetchAll(PDO::FETCH_COLUMN);
		}		
		return $fields;
	}		

	public static function insert(array $data): object|bool
	{
		self::$instance = self::instance();

		if (empty($data)) {
			self::$isAnyException = true;		
			throw new Exception("Cannot insert empty data", 1);
			return false;
		}

		// Storing query Params
		$counter = self::$queryParamsCounter;
		$dataKeysArray = [];
		$paramValuesArray = [];		
		array_walk($data, function($value, $key) use (&$counter, &$dataKeysArray, &$paramValuesArray) {
			if (!isset($counter[$key])) {
				$counter[$key] = 0;
			} else {
				$counter[$key]++;
			}
			$_key = ($counter[$key] == 0) ? $key : $key . $counter[$key];
			$paramValuesArray[":" . $_key] = $value;
			$dataKeysArray[] = sprintf(":%s", $_key);
		});
		self::$queryParamsCounter = $counter;
		$fieldKeysArray = array_keys($data);
		$fieldKeys = implode(",", $fieldKeysArray);
		$preparedKeys = implode(",", $dataKeysArray);
		self::$queryParams = array_merge(self::$queryParams, $paramValuesArray);

		// Writing query
		if (self::$lastStatement !== '') {
			self::$isAnyException = true;		
			throw new Exception("INSERT statement cannot be used with or before" . self::$lastStatement, 1);
			return false;
		}		
		self::$query = sprintf("INSERT INTO %s (%s) VALUES (%s)", self::$tableName, $fieldKeys, $preparedKeys);		
		self::$lastStatement = "INSERT"; // useful for next query method chaining
		$result = self::execute();
		self::resetInstance();
		return $result;
	}

	public static function update(array $data): object|bool
	{
		self::$instance = self::instance();

		if (empty($data)) {
			self::$isAnyException = true;		
			throw new Exception("Cannot update empty data", 1);
			return false;
		}

		// Storing query Params
		$counter = self::$queryParamsCounter;
		$dataKeysArray = [];
		$paramValuesArray = [];		
		array_walk($data, function($value, $key) use (&$counter, &$dataKeysArray, &$paramValuesArray) {
			if (!isset($counter[$key])) {
				$counter[$key] = 0;
			} else {
				$counter[$key]++;
			}
			$_key = ($counter[$key] == 0) ? $key : $key . $counter[$key];
			$paramValuesArray[":" . $_key] = $value;
			$dataKeysArray[] = sprintf("%s = :%s", $key, $_key);
		});
		self::$queryParamsCounter = $counter;
		$preparedKeys = implode(",", $dataKeysArray);
		self::$queryParams = array_merge(self::$queryParams, $paramValuesArray);

		// Writing query
		if (self::$lastStatement !== '') {
			self::$isAnyException = true;		
			throw new Exception("UPDATE statement cannot be used with or before" . self::$lastStatement, 1);
			return false;
		}		
		self::$query = sprintf("UPDATE %s SET %s ", self::$tableName, $preparedKeys);		
		self::$lastStatement = "UPDATE"; // useful for next query method chaining		
		return self::$instance;
	}

	public static function delete(mixed $fields): bool
	{
		self::$instance = self::instance();
		$primaryKey = '';

		if (empty($fields)) {
			self::$isAnyException = true;		
			throw new Exception("Cannot perform delete operation for empty condition", 1);
			return false;
		}

		// Writing query
		if (self::$lastStatement !== '') {
			self::$isAnyException = true;		
			throw new Exception("DELETE statement cannot be used with or before" . self::$lastStatement, 1);
			return false;
		}	

		self::$query = sprintf("DELETE FROM %s ", self::$tableName);		
		self::$lastStatement = "DELETE"; // useful for next query method chaining

		if (is_bool($fields)) {
			self::where($fields);
		}

		// Fields might be a referred to primaryKey
		if (!is_array($fields)) {
			$primaryKey = empty(self::$primaryKey) ? static::$primaryKey : self::$primaryKey;
			if (empty($primaryKey)) {
				self::$isAnyException = true;		
				throw new Exception("Primary key is not set", 1);
				return false;
			}
			
			self::where([$primaryKey => $fields]);
		} else {
			self::where($fields);
		}

		$result = self::execute();
		self::resetInstance();
		return $result;
	}

	public static function select(...$args): object
	{
		self::$instance = self::instance();

		$args = func_get_args();

		$flattenArray = [];
		array_walk_recursive($args, function ($value) use (&$flattenArray) {
			$flattenArray[] = $value;
		});
		$fields = implode(",", $flattenArray);

		if (empty($fields)) {
			self::$isAnyException = true;		
			throw new Exception("Cannot SELECT without fields", 1);
			die();
		}
		
		$fields = trim($fields);

		self::$query = sprintf(" SELECT %s FROM %s ", $fields, self::$tableName);
		self::$lastStatement = "SELECT"; // useful for next query method chaining
		return self::$instance;
	}

	public static function where(array|bool $fields): mixed
	{
		self::$instance = self::instance();

		return self::whereAnd($fields);
	}

	public static function whereOr(array|bool $fields): mixed
	{
		self::$instance = self::instance();

		if (empty($fields)) {
			self::$isAnyException = true;		
			throw new Exception("Where condition cannot be empty", 1);
			return false;
		}
		
		self::$query = trim(self::$query);

		if (is_bool($fields)) {
			self::$query .= sprintf(" WHERE %s ", $fields);		
			self::$lastStatement = "WHERE"; // useful for next query method chaining
			return self::$instance;
		}

		// Storing query Params
		$counter = self::$queryParamsCounter;
		$fieldKeysArray = [];
		$paramValuesArray = [];		
		array_walk($fields, function($value, $key) use (&$counter, &$fieldKeysArray, &$paramValuesArray) {
			if (!isset($counter[$key])) {
				$counter[$key] = 0;
			} else {
				$counter[$key]++;
			}
			$_key = ($counter[$key] == 0) ? $key : $key . $counter[$key];
			$paramValuesArray[":" . $_key] = $value;
			$fieldKeysArray[] = sprintf("%s = :%s", $key, $_key);
		});
		self::$queryParamsCounter = $counter;
		$preparedKeys = implode(" OR ", $fieldKeysArray);
		self::$queryParams = array_merge(self::$queryParams, $paramValuesArray);

		// Writing query		
		if (self::$lastStatement === "WHERE") {
			self::$query .= sprintf(" OR %s ", $preparedKeys);		
		} else {
			self::$query .= sprintf(" WHERE %s ", $preparedKeys);		
		}
		self::$lastStatement = "WHERE"; // useful for next query method chaining
		return self::$instance;
	}

	public static function whereAnd(array|bool $fields): mixed
	{
		self::$instance = self::instance();

		if (empty($fields)) {
			self::$isAnyException = true;		
			throw new Exception("Where condition cannot be empty", 1);
			return false;
		}
		
		self::$query = trim(self::$query);

		if (is_bool($fields)) {
			self::$query .= sprintf(" WHERE %s ", $fields);		
			self::$lastStatement = "WHERE"; // useful for next query method chaining
			return self::$instance;
		}

		// Storing query Params
		$counter = self::$queryParamsCounter;
		$fieldKeysArray = [];
		$paramValuesArray = [];		
		array_walk($fields, function($value, $key) use (&$counter, &$fieldKeysArray, &$paramValuesArray) {
			if (!isset($counter[$key])) {
				$counter[$key] = 0;
			} else {
				$counter[$key]++;
			}
			$_key = ($counter[$key] == 0) ? $key : $key . $counter[$key];
			$paramValuesArray[":" . $_key] = $value;
			$fieldKeysArray[] = sprintf("%s = :%s", $key, $_key);
		});
		self::$queryParamsCounter = $counter;
		$preparedKeys = implode(" AND ", $fieldKeysArray);
		self::$queryParams = array_merge(self::$queryParams, $paramValuesArray);

		// Writing query
		if (self::$lastStatement === "WHERE") {
			self::$query .= sprintf(" AND %s ", $preparedKeys);		
		} else {
			self::$query .= sprintf(" WHERE %s ", $preparedKeys);		
		}
		self::$lastStatement = "WHERE"; // useful for next query method chaining
		return self::$instance;
	}

	public static function groupBy(...$args): mixed
	{
		self::$instance = self::instance();

		$args = func_get_args();

		$flattenArray = [];
		array_walk_recursive($args, function ($value) use (&$flattenArray) {
			$flattenArray[] = $value;
		});
		$groupColumns = implode(",", $flattenArray);

		// Writing query
		self::$query = trim(self::$query);
		self::$query .= sprintf(" GROUP BY %s ", $groupColumns);
		self::$lastStatement = "GROUP"; // useful for next query method chaining
		return self::$instance;
	}

	public static function orderAsc(...$args): mixed
	{
		self::$instance = self::instance();

		$args = func_get_args();

		$flattenArray = [];
		array_walk_recursive($args, function ($value) use (&$flattenArray) {
			$flattenArray[] = $value;
		});
		$orderColumns = implode(",", $flattenArray);

		// Writing query
		self::$query = trim(self::$query);
		self::$query .= sprintf(" ORDER BY %s ASC ", $orderColumns);
		self::$lastStatement = "ORDER"; // useful for next query method chaining
		return self::$instance;
	}

	public static function orderDesc(...$args): mixed
	{
		self::$instance = self::instance();

		$args = func_get_args();

		$flattenArray = [];
		array_walk_recursive($args, function ($value) use (&$flattenArray) {
			$flattenArray[] = $value;
		});
		$orderColumns = implode(",", $flattenArray);

		// Writing query
		self::$query = trim(self::$query);
		self::$query .= sprintf(" ORDER BY %s DESC ", $orderColumns);
		self::$lastStatement = "ORDER"; // useful for next query method chaining
		return self::$instance;
	}

	public static function limit(int $offset, int $length = 0): mixed
	{
		self::$instance = self::instance();

		if (empty($offset) && empty($length)) {
			self::$isAnyException = true;		
			throw new Exception("Limit cannot be an empty", 1);
			return false;
		}

		self::$query = trim(self::$query);

		if (empty($length)) {
			self::$query .= sprintf(" LIMIT %d ", $offset);
		} else {
			self::$query .= sprintf(" LIMIT %d, %d ", $offset, $length);
		}
		self::$lastStatement = "LIMIT"; // useful for next query method chaining
		return self::$instance;
	}

	public static function get(): mixed
	{
		if (self::$queryExecuted == false) {
			self::execute();
			$result = self::$statement->fetch(PDO::FETCH_ASSOC);			
			self::resetInstance();
			return $result;
		}
		return false;
	}

	public static function getAll(): mixed
	{
		if (self::$queryExecuted == false) {
			self::execute();
			$result = self::$statement->fetchAll(PDO::FETCH_ASSOC);			
			self::resetInstance();
			return $result;			
		}
		return false;
	}

	public static function set(): bool
	{
		if (self::$queryExecuted == false) {
			$result = self::execute();
			self::resetInstance();
			return $result;
		}
		return false;
	}

	private static function execute()
	{
		// start transaction
		self::$db->startTransaction();

		self::$statement = self::$db->prepare(self::$query);		
		
		// bind value from query params
		if (!empty(self::$queryParams)) {
			foreach (self::$queryParams as $key => $value) {
				self::$statement->bindValue($key, $value);
			}
		}

		// try to execute the query
		try {
			if (self::$statement->execute()) {
				self::$db->commitTransaction();
				self::$queryExecuted = true;
				self::$isAnyException = false;
				return true;
			} else {
				self::$db->rollBackTransaction();
				return false;
			}
		} catch (Exception $e) {
			self::$db->rollBackTransaction();
			return false;
		}
	}

	public function __destruct()
	{
		// invokes execute method if it is not invoked yet
		if (self::$queryExecuted == false && self::$isAnyException == false) {			
			self::execute();
		}
		self::resetInstance();
	}

}