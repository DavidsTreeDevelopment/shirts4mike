<?php

//This try catch block connects to the database
try {
	$db = new PDO("mysql:host=localhost; dbname=shirts4mike; port=3306", "root");
	//This method called on the PDO object tells the PDO to throw an error for bad sql commands.
	//The double colon means that we want to change the error mode
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//run a sql command to the db that the object is connected to
	$db->exec("SET NAMES 'utf8'");
	

} catch (Exception $e) {
	echo "Could not connect to the database.";
	exit;
}

//This block of code runs a query against a DB
//have a separate try catch block for each point
//of interation with a server
//This point of interaction runs the query
//that returns the products.
//This method call returns the result of the query
// the return value is a new kind of PHP object, a PDOStatement object. The object has one property, our query

try {
	$results = $db->query("SELECT name, price, img, sku, paypal FROM products ORDER BY sku ASC");
	
} catch (Exception $e) {
	echo "Data could not be retrieved from the database.";
	exit;
}

?>