<?php

http_response_code(500);
if (isset($e) && is_object($e) && method_exists($e, 'getMessage'))
{
	echo "<h1>Attention!</h1>";				
	echo "<h4>Internal Server Error : " . $e->getMessage() . "</h4>";

	echo "<p>at <u>" . $e->getFile() . "</u> on " . $e->getLine() . "</p>";
	echo "<h4>Trace Levels:</h4>";
	echo "<pre>" . $e->getTraceAsString() . "</pre>";		
}	else {
	echo "<h1>Attention!</h1>";					
	echo "<p>Something went wrong...<br>";
	echo "<p>Please, Try later.</p>";
}