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

use roots\app\core\Database;
use roots\app\core\Logger;
use \PDO;
use \PDOStatement;
use \Exception;

/**
 * Class Model
 *
 * Which has properties and methods to establish database model operations.
 * It has instance, resetInstance, _set, _get, initModelProperties, getTable, getFields
 * insert, update, delete, select, where, whereOr, whereAnd, groupBy, orderAsc, orderDesc
 * limit, get, getAll, set and execute methods
 *
 */
class Model
{

	/**
	 * The singleton instance of the Model class.
	 *
	 * @var Model|null
	 */
	private static ?Model $instance;

	/**
	 * The Database instance used by the Model.
	 *
	 * @var Database
	 */
	protected static Database $db; 

	/**
	 * The data array for the model.
	 *
	 * @var array
	 */
	protected array $d = [];

	/**
	 * The name of the database table associated with the model.
	 *
	 * @var string
	 */
	protected static string $tableName = '';

	/**
	 * An array containing the names of the fields in the database table.
	 *
	 * @var array
	 */
	protected static array $fields = [];

	/**
	 * The name of the primary key field in the database table.
	 *
	 * @var string
	 */
	protected static string $primaryKey = '';

	/**
	 * The current SQL query being built.
	 *
	 * @var string
	 */
	private static string $query =  '';

	/**
	 * The parameters for the current SQL query.
	 *
	 * @var array
	 */
	private static array $queryParams;

	/**
	 * A counter for named query parameters to prevent duplicates.
	 *
	 * @var array
	 */
	private static array $queryParamsCounter;

	/**
	 * The last executed SQL statement type.
	 *
	 * @var string
	 */
	private static string $lastStatement;

	/**
	 * The PDOStatement object for the prepared query.
	 *
	 * @var PDOStatement|null
	 */
	private static ?PDOStatement $statement;

	/**
	 * A flag indicating whether any exception occurred during query execution.
	 *
	 * @var bool
	 */
	private static bool $isAnyException;

	/**
	 * A flag indicating whether a query has been executed.
	 *
	 * @var bool
	 */
	private static bool $queryExecuted;

	/**
	 * Constructor for the Model class.
	 *
	 * Initializes the model instance, database connection, and other properties.
	 */
	public function __construct()
	{
		// Set the singleton instance.
		self::$instance = $this;

		// Get the database instance.
		self::$db = Database::instance();
		
		// Set PDO error mode to exception.
		self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// Initialize model properties (table name, fields, primary key).
		self::initModelProperties();		

		// Initialize query parameters and flags.
		self::$queryParams = [];
		self::$queryParamsCounter = []; // Initialize parameter counter.
		self::$isAnyException = false;
		self::$queryExecuted = false;
		self::$lastStatement = '';		
	}

	/**
	 * Returns the singleton instance of the Model class.
	 *
	 * @return static The singleton instance of static class.
	 */
	public static function instance(): static
	{
		// Check if an instance already exists.
		if (!isset(self::$instance)) {
			// Create a new instance using late static binding (important for inheritance).
			self::$instance = new static();
		}

		// Return the singleton instance.
		return self::$instance;
	}

	/**
	 * Resets the singleton instance of the Model class. 
	 *
	 * @return void
	 */
	private static function resetInstance(): void
	{
		self::$instance = null;
	}

	/**
	 * Sets a value in the model's data array.
	 *
	 * @param int|string $key The key to set.
	 * @param mixed $value The value to set.
	 * @return void
	 */
	public function _set(int|string $key, mixed $value = ''):	void
	{
		$this->d[$key] = $value;
	}

	/**
	 * Retrieves a value from the model's data array.
	 *
	 * @param int|string $key The key to retrieve.
	 * @return mixed The value associated with the key, or null if the key does not exist.
	 */
	public function _get(int|string $key): mixed
	{
		return $this->d[$key] ?? null;
	}

	/**
	 * Initializes the model properties (table name, fields, and primary key).
	 *
	 * This method determines the table name and fields based on either explicitly
	 * defined static properties or by calling the `getTable()` and `getFields()`
	 * methods. It throws an exception if the table name or fields cannot be determined.
	 *
	 * @throws Exception If the model table name or fields are not specified.
	 */
	public static function initModelProperties(): void
	{		
		// Determine the table name.
		if (isset(static::$tableName) && !empty(static::$tableName)) {
			self::$tableName = static::$tableName;
		} else {
			self::$tableName = self::getTable();
		}

		// Determine the fields.
		if (isset(static::$fields) && !empty(static::$fields)) {
			self::$fields = static::$fields;
		} else {
			self::$fields = self::getFields();
		}

		// Validate table name and fields.
		if (empty(self::$tableName)) {
			self::$isAnyException = true;
			throw new Exception("Model Table Name Not Specified Error", 1);
		}

		if (empty(self::$fields)) {	
			self::$isAnyException = true;		
			throw new Exception("Model Fields Not Specified Error", 1);
		}
	}

	/**
	 * Determines the table name based on the class name.
	 *
	 * This method extracts the table name from the fully qualified class name.
	 * It assumes the table name is the last part of the class name after removing the namespace.
	 *
	 * @return string The table name.
	 */
	private static function getTable(): string
	{
		$tableName = get_called_class(); // Get the fully qualified class name.
		$tableName = explode("\\", $tableName); // Split the class name by namespace separators.
		$tableName = array_pop($tableName); // Get the last part of the array, which is the class name (and assumed table name).
	
		return $tableName;
	}

	/**
	 * Retrieves the list of fields (columns) for the table.
	 *
	 * This method executes a `SHOW COLUMNS` query to get the names of all columns
	 * in the table associated with the model.
	 *
	 * @return array An array containing the names of the fields.
	 */
	private static function getFields(): array
	{
		$fields = [];
		$query = "SHOW COLUMNS FROM " . self::$tableName;		

		try {
        $statement = self::$db->prepare($query);
        if ($statement->execute()) {
            $fields = $statement->fetchAll(PDO::FETCH_COLUMN);
        }
    } catch (PDOException $e) {
        // Handle the exception appropriately, e.g., log it or re-throw it.
        self::$isAnyException = true; // Set the exception flag.
        Logger::logError($e); // Log the error.        
        throw $e; 
        return []; // Return an empty array to indicate failure.
    }
	}		

	/**
	 * Inserts a new record into the database table.
	 *
	 * @param array $data An associative array containing the data to insert, 
	 * where keys are column names and values are the corresponding values.
	 * @return bool Returns boolean on success or false on failure.
	 * @throws Exception If the provided data is empty 
	 * or if an INSERT statement is used after another statement.
	 */
	public static function insert(array $data): object|bool
	{
		// Get model instance
		self::$instance = self::instance();

		if (empty($data)) {
			self::$isAnyException = true;		
			throw new Exception("Cannot insert empty data", 1);
			return false;
		}

		// Prepare query parameters (handling duplicate keys).
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
			$dataKeysArray[] = ":" . $_key;
		});

		self::$queryParamsCounter = $counter;
		$fieldKeysArray = array_keys($data);
		$fieldKeys = implode(",", $fieldKeysArray);
		$preparedKeys = implode(",", $dataKeysArray);
		self::$queryParams = array_merge(self::$queryParams, $paramValuesArray);

		// Build the query.
		if (self::$lastStatement !== '') {
			self::$isAnyException = true;		
			throw new Exception("INSERT statement cannot be used with or before" . self::$lastStatement, 1);
			return false;
		}		

		self::$query = sprintf("INSERT INTO %s (%s) VALUES (%s)", self::$tableName, $fieldKeys, $preparedKeys);		
		self::$lastStatement = "INSERT"; // useful for next query method chaining
		
		// Execute the query and return the result.
		$result = self::execute();
		self::resetInstance(); // Reset the instance after the query is executed.
		return $result; // Return the instance on success, false on failure.
	}

	/**
	 * Updates records in the database table.
	 *
	 * @param array $data An associative array containing the data to update, 
	 * where keys are column names and values are the corresponding values.
	 * @return object|bool Returns the Model instance on success 
	 * (allowing for method chaining), or false on failure.
	 * @throws Exception If the provided data is empty 
	 * or if an UPDATE statement is used after another statement without a `where` clause.
	 */
	public static function update(array $data): object|bool
	{
		// Get model instance
		self::$instance = self::instance();

		// Check for empty data and throws exception if it is an empty
		if (empty($data)) {
			self::$isAnyException = true;		
			throw new Exception("Cannot update empty data", 1);
			return false;
		}

		// Prepare query parameters (handling duplicate keys).
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

		// Build the query.
		if (self::$lastStatement !== '') {
			self::$isAnyException = true;		
			throw new Exception("UPDATE statement cannot be used with or before" . self::$lastStatement, 1);
			return false;
		}		

		self::$query = sprintf("UPDATE %s SET %s ", self::$tableName, $preparedKeys);		
		self::$lastStatement = "UPDATE"; // useful for next query method chaining		
		return self::$instance; // Return the instance for chaining.  Execution happens later.
	}

	/**
	 * Deletes records from the database table.
	 *
	 * @param mixed $fields The criteria for deleting records. Can be:
	 *                     - A boolean (true/false) to delete all records (use with caution!).
	 *                     - A single value representing the primary key value to delete.
	 *                     - An associative array where keys are column names and values are the 
   *                       corresponding values for the WHERE clause.
	 * @return bool True on success, false on failure.
	 * @throws Exception If the delete condition is empty 
	 * or if a DELETE statement is used after another statement.
	 */
	public static function delete(mixed $fields): bool
	{
		// Get model instance
		self::$instance = self::instance();

		$primaryKey = '';

		// Check for empty $fields and throws exception if it is an empty
		if (empty($fields)) {
			self::$isAnyException = true;		
			throw new Exception("Cannot perform delete operation for empty condition", 1);
			return false;
		}

		// Check for prior unfinished statement.
		if (self::$lastStatement !== '') {
			self::$isAnyException = true;		
			throw new Exception("DELETE statement cannot be used with or before" . self::$lastStatement, 1);
			return false;
		}	

		self::$query = sprintf("DELETE FROM %s ", self::$tableName);		
		self::$lastStatement = "DELETE"; // useful for next query method chaining

		// Handle different types of $fields input.
		if (is_bool($fields)) {
			self::where($fields); // Delete all if $fields is true. Be very careful with this!
		} else if (!is_array($fields)) { // Single value, assumed to be primary key.
			$primaryKey = empty(self::$primaryKey) ? static::$primaryKey : self::$primaryKey;
			if (empty($primaryKey)) {
				self::$isAnyException = true;		
				throw new Exception("Primary key is not set", 1);
				return false;
			}
			
			self::where([$primaryKey => $fields]);
		} else { // Associative array for WHERE clause.
			self::where($fields);
		}

		$result = self::execute(); // Delete record by executing the query
		self::resetInstance(); // resets model instance 
		return $result; // returns boolean
	}

	/**
	 * Starts a SELECT query.
	 *
	 * @param mixed ...$args The fields to select.  
	 * Can be passed as separate arguments or as an array.
	 * 
	 * @return static The Model instance (for method chaining).
	 * @throws Exception If no fields are specified.
	 */
	public static function select(...$args): object
	{
		// Get model instance
		self::$instance = self::instance();

		// Flatten the arguments array (handles both separate arguments and array input).
		$flattenArray = [];
		array_walk_recursive($args, function ($value) use (&$flattenArray) {
			$flattenArray[] = $value;
		});
		$fields = implode(",", $flattenArray);

		// Check for empty fields and throws exception if it is an empty
		if (empty($fields)) {
			self::$isAnyException = true;		
			throw new Exception("Cannot SELECT without fields", 1);			
		}
		
		// trims an whitespace
		$fields = trim($fields);

		// Building a query
		self::$query = sprintf(" SELECT %s FROM %s ", $fields, self::$tableName);
		self::$lastStatement = "SELECT"; // useful for next query method chaining
		return self::$instance; // returns model instance
	}

	/**
	 * Adds a WHERE clause to the query.
	 *
	 * @param array|bool $fields The conditions for the WHERE clause. It can be:
	 *                       - A boolean `true` to select all (equivalent to no WHERE clause). 
   *                         Be extremely cautious with `where(true)` as it affects all rows.
	 *                       - A boolean `false` will throw an exception.
	 *                       - An associative array where keys are column names and values are the 
	 *                         corresponding values.
	 * @return static The Model instance (for method chaining).
	 * @throws Exception If the WHERE clause is invalid.
	 */
	public static function where(array|bool $fields): mixed
	{
		// Get model instance
		self::$instance = self::instance();

		return self::whereAnd($fields); // invokes whereAnd method
	}

	/**
	 * Adds a WHERE clause with OR conditions to the query.
	 *
	 * @param array|bool $fields The conditions for the WHERE clause. It can be:
	 *                       - A boolean `true` to select all (equivalent to no WHERE clause). 
	 *                         Be extremely cautious with `where(true)` as it affects all rows.
	 *                       - An associative array where keys are column names and values are the 
	 *                         corresponding values.
	 * @return static The Model instance (for method chaining).
	 * @throws Exception If the WHERE clause is invalid.
	 */
	public static function whereOr(array|bool $fields): mixed
	{
		// Get model instance
		self::$instance = self::instance();

		// Handle empty conditions.
		if (empty($fields)) {
			self::$isAnyException = true;		
			throw new Exception("Where condition cannot be empty", 1);
			return false;
		}
		
		// Trim whitespace from the current query.
		self::$query = trim(self::$query);

		// Handle boolean input.
		if (is_bool($fields)) {
			self::$query .= sprintf(" WHERE %s ", $fields);		
			self::$lastStatement = "WHERE"; // useful for next query method chaining
			return self::$instance;
		}

		// Prepare query parameters (handling duplicate keys).
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

		// Build the query.	
		if (self::$lastStatement === "WHERE") { // Checks if already have where clause then treats as another or condition 
			self::$query .= sprintf(" OR %s ", $preparedKeys);		
		} else {
			self::$query .= sprintf(" WHERE %s ", $preparedKeys);		
		}

		self::$lastStatement = "WHERE"; // useful for next query method chaining
		return self::$instance; // Return the instance for chaining.
	}

	/**
	 * Adds a WHERE clause with AND conditions to the query.
	 *
	 * @param array|bool $fields The conditions for the WHERE clause. It can be:
	 *                       - A boolean `true` to select all (equivalent to no WHERE clause). 
	 *                         Be extremely cautious with `where(true)` as it affects all rows.
	 *                       - An associative array where keys are column names and values are the 
	 *                         corresponding values.
	 * @return static The Model instance (for method chaining).
	 * @throws Exception If the WHERE clause is invalid.
	 */
	public static function whereAnd(array|bool $fields): mixed
	{
		// Get model instance
		self::$instance = self::instance();

		// Handle empty conditions.
		if (empty($fields)) {
			self::$isAnyException = true;		
			throw new Exception("Where condition cannot be empty", 1);
			return false;
		}
		
		// Trim whitespace from the current query.
		self::$query = trim(self::$query);

		// Handle boolean input
		if (is_bool($fields)) {
			self::$query .= sprintf(" WHERE %s ", $fields);		
			self::$lastStatement = "WHERE"; // useful for next query method chaining
			return self::$instance;
		}

		// Prepare query parameters (handling duplicate keys).
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

		// Build the query.
		if (self::$lastStatement === "WHERE") { // Checks if already have where clause then treats as another and condition 
			self::$query .= sprintf(" AND %s ", $preparedKeys);		
		} else {
			self::$query .= sprintf(" WHERE %s ", $preparedKeys);		
		}
		self::$lastStatement = "WHERE"; // useful for next query method chaining
		return self::$instance; // Return the instance for chaining.
	}

	/**
	 * Adds a GROUP BY clause to the query.
	 *
	 * @param mixed ...$args The columns to group by. 
	 * It can be passed as separate arguments or as an array.
	 * 
	 * @return static The Model instance (for method chaining).
	 */
	public static function groupBy(...$args): mixed
	{
		// Get model instance
		self::$instance = self::instance();

		// Flatten the arguments array.
		$flattenArray = [];
		array_walk_recursive($args, function ($value) use (&$flattenArray) {
			$flattenArray[] = $value;
		});

		// Implode columns into comma separated string.
		$groupColumns = implode(",", $flattenArray);

		// Build the query.
		self::$query = trim(self::$query); // Trim whitespace
		self::$query .= sprintf(" GROUP BY %s ", $groupColumns);
		self::$lastStatement = "GROUP"; // useful for next query method chaining
		return self::$instance; // Return the instance for chaining.
	}

	/**
	 * Adds an ORDER BY clause with ascending order to the query.
	 *
	 * @param mixed ...$args The columns to order by. 
	 * It can be passed as separate arguments or as an array.
	 *
	 * @return static The Model instance (for method chaining).
	 */
	public static function orderAsc(...$args): mixed
	{
		// Get model instance
		self::$instance = self::instance();

		// Flatten the arguments array.
		$flattenArray = [];
		array_walk_recursive($args, function ($value) use (&$flattenArray) {
			$flattenArray[] = $value;
		});
		// Implode columns into comma separated string and trim whitespace.
		$orderColumns = implode(",", $flattenArray);
		self::$query = trim(self::$query);

		// Build the query.
		self::$query .= sprintf(" ORDER BY %s ASC ", $orderColumns);
		self::$lastStatement = "ORDER"; // useful for next query method chaining
		return self::$instance; // Return the instance for chaining.
	}

	/**
	 * Adds an ORDER BY clause with descending order to the query.
	 *
	 * @param mixed ...$args The columns to order by. 
	 * It can be passed as separate arguments or as an array.
	 * 
	 * @return static The Model instance (for method chaining).
	 */
	public static function orderDesc(...$args): mixed
	{
		// Get model instance
		self::$instance = self::instance();

		// Flatten the arguments array.
		$flattenArray = [];
		array_walk_recursive($args, function ($value) use (&$flattenArray) {
			$flattenArray[] = $value;
		});

		// Implode columns into comma separated string and trim whitespace.
		$orderColumns = implode(",", $flattenArray);
		self::$query = trim(self::$query);

		// Build the query.
		self::$query .= sprintf(" ORDER BY %s DESC ", $orderColumns);
		self::$lastStatement = "ORDER"; // useful for next query method chaining
		return self::$instance; // Return the instance for chaining.
	}

	/**
	 * Adds a LIMIT clause to the query.
	 *
	 * @param int $offset The offset for the limit.
	 * @param int $length The number of rows to return (optional).
	 *
	 * @return static The Model instance (for method chaining).
	 * @throws Exception If both $offset and $length are empty.
	 */
	public static function limit(int $offset, int $length = 0): mixed
	{
		// Get model instance
		self::$instance = self::instance();

		// Check for empty offset and length
		if (empty($offset) && empty($length)) {
			self::$isAnyException = true;		
			throw new Exception("Limit cannot be an empty", 1);
			return false;
		}

		// Trim the query statement
		self::$query = trim(self::$query);

		// Build the query.
		if (empty($length)) { // If the length is an empty then it will treated as number of rows
			self::$query .= sprintf(" LIMIT %d ", $offset);
		} else { // It will treated as number of rows with offset
			self::$query .= sprintf(" LIMIT %d, %d ", $offset, $length);
		}

		self::$lastStatement = "LIMIT"; // useful for next query method chaining
		return self::$instance; // Return the instance for chaining.
	}

	/**
	 * Executes the query and retrieves the first row as an associative array.
	 *
	 * @return mixed An associative array representing the first row of the result set, 
	 * or false if the query fails or no rows are found.
	 */
	public static function get(): mixed
	{
		if (self::$queryExecuted == false) {
			self::execute();
			$result = self::$statement->fetch(PDO::FETCH_ASSOC);			
			self::resetInstance(); // Reset after fetching
			return $result; // Return Associative Array Or null
		}
		return false; // Return false when query is not executed
	}

	/**
	 * Executes the query and retrieves all rows as an array of associative arrays.
	 *
	 * @return mixed An array of associative arrays, 
	 * where each array represents a row in the result set. 
	 * Returns false on failure.
	 */
	public static function getAll(): mixed
	{
		if (self::$queryExecuted == false) {
			self::execute();
			$result = self::$statement->fetchAll(PDO::FETCH_ASSOC);			
			self::resetInstance(); // Reset after fetching.
			return $result;	// Return Associative Array Or null.
		}
		return false; // Return false when query is not executed.
	}

	/**
	 * Executes the built query for UPDATE and returns the success status.
	 *
	 * @return bool True on successful execution, false on failure.
	 */
	public static function set(): bool
	{
		if (self::$queryExecuted == false) {
			$result = self::execute();
			self::resetInstance(); // Reset after execution.
			return $result;
		}
		return false; // Return false if it is not executed.
	}

	/**
	 * Executes the prepared statement.
	 *
	 * @return bool True on successful execution, false on failure.
	 */
	private static function execute()
	{
		// Start transaction.
		self::$db->startTransaction();

		// Preparing built query
		self::$statement = self::$db->prepare(self::$query);		
		
		// Bind values from query parameters.
		if (!empty(self::$queryParams)) {
			foreach (self::$queryParams as $key => $value) {
				self::$statement->bindValue($key, $value);
			}
		}

		// Execute the query.
		try {
			if (self::$statement->execute()) {
				// Commits the changes when query executed without any errors.
				self::$db->commitTransaction();
				self::$queryExecuted = true;
				self::$isAnyException = false;
				return true;
			} else {
				// Rollbacks the changes when query is not executed due to any errors.
				self::$db->rollBackTransaction();
				return false;
			}
		} catch (Exception $e) {
			// Rollbacks the changes when query is not executed due to any errors.
			self::$db->rollBackTransaction();
			return false;
		}
	}

	/**
	 * Destructor for the Model class.
	 *
	 * Executes the query if it hasn't been executed yet and no exceptions have occurred.
	 * Resets the model instance.
	 *
	 * @throws Exception If query execution fails in the destructor. 
	 */
	public function __destruct()
	{
		// Execute the query if it hasn't been executed 
		// And no exceptions occurred during query build process.
		if (self::$queryExecuted == false && self::$isAnyException == false) {			
			self::execute(); // Executes the query before end of the model.
		}
		self::resetInstance(); // Reset after execution.
	}

}