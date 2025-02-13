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

namespace roots\app\models;

use roots\app\core\Model;
use \Exception;

/**
 * Class Demo
 *
 * Which has properties and methods to handle articles table.
 * It has properties to update $tableName, $fields and $primaryKey 
 * for various database related operations
 *
 */
class Demo extends Model
{
	/**
	 * Table name property for current model
	 *
	 * @var string 
	 */
	protected static string $tableName = 'articles';	

	/**
	 * Table Fields property for current model
	 *
	 * @var array
	 */
	protected static array $fields = ['id', 'article_title', 'content'];

	/**
	 * Primary key for indexing
	 *
	 * @var string
	 */
	protected static string $primaryKey = 'id';
}