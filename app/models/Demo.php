<?php

namespace roots\app\models;

use roots\app\core\Model;
use \Exception;

/**
 * Articles class
 */
class Demo extends Model
{
	// tableName protected static property
	protected static string $tableName = 'articles';	

	// fields array protected static property
	protected static array $fields = ['id', 'article_title', 'content'];
	// , 'author', 'views', 'comments', 'created_at', 'updated_at'];

	// fields string protected static property 
	protected static string $primaryKey = 'id';
}