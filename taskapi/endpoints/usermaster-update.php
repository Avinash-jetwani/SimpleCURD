<?php
error_reporting(E_ALL & ~E_WARNING );
//This is the update API, which will update the data of a user. 

// Adding header to overcome CORS error.

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

include_once '../config/dbclass.php'; // adding our db file

include_once '../entities/usermaster.php'; // class file

$dbclass = new DBClass();
try {
	$connection = $dbclass->getConnection();
	$user = new USER($connection);
	$data = json_decode(file_get_contents("php://input")); // request data stored in $data
	$user->user_name = $data->user_name;
	$user->user_id = $data->user_id;
	$user->user_email = $data->user_email;
	$user->user_phone = $data->user_phone;
	$user->age = $data->age;
	$user->city = $data->city;
	$user->post_code = $data->post_code;
	$user->address = $data->address;

	// Calling class method, which will update the data into database table.
	$result = $user->update_user();

	if($result == 0)
	{
		$Response->status="Success";
		$Response->msg="Updated";	
	}
	else if($result == 1)
	{
		$Response->status="Fali";
		$Response->msg="Invalid Moble Number";	
	}
	else
	{
	    $Response->status="Fail";
		$Response->msg="Try after sometime";
	}
	
	echo json_encode($Response); // Returning the resposne to the AJAX.


} catch (Exception $e) {
	$Response->status="Fail";
	$Response->msg="Try after sometime";
	$Response->err=$e;
	echo json_encode($Response);
}
?>