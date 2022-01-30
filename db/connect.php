<?php

$host='';
$db='';
$user='';
$pwd='';

require '../../resources/config.php';
function connect($host, $db, $user, $pwd)
{
	$dsn = "mysql:host=$host;dbname=$db;charset=UTF8";
	try {
		$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
		return new PDO($dsn, $user, $pwd, $options);

	} catch (PDOException $e) {
		die($e->getMessage());
	}
}

return connect($host, $db, $user, $pwd);