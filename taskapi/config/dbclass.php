<?php
class DBClass {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "blocksonline";
    public $connection;  
    // This will establish connection to the database and returns dbConnection.
    public function getConnection()
    {
    $dbhost=$this->host;
	$dbuser=$this->username;
	$dbpass=$this->password;
	$dbname=$this->database;
	$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbConnection->exec("set names utf8");
	$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbConnection;
    }
}
