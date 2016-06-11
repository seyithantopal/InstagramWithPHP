<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbName = 'instagram';
try{
	$db = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8",$user,$password);
}
catch(PDOException $e){
	echo $e->getMessage();
}
?>