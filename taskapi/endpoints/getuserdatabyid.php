<?php
error_reporting(E_ALL & ~E_WARNING );
//This will get the data of a particular user. 

// Adding header to overcome CORS error.

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

include_once '../config/dbclass.php'; // adding our db file

include_once '../entities/usermaster.php'; // class file

$dbclass = new DBClass();

$connection = $dbclass->getConnection();

$user = new USER($connection);

$data = json_decode(file_get_contents("php://input")); // request data stored in $data

$user->user_id = $data->user_id; // Calling a class method will retrive the data of a particular user ID.

$Response = $user->get_user_data_byid(); 
echo json_encode($Response); // Returning the resposne to the AJAX.

?>
