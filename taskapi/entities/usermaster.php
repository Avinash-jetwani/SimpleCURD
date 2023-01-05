<?php
	include_once '../libphonenumber/PhoneNumberUtil.php'; 
	
	class USER
	{
		// Creating data members of the class.
		private $connection;
		public $user_id;
		public $user_name;
		public $user_password;
		public $user_email;
		public $user_phone;
		public $age;
		public $registration_date;
		public $city;
		public $post_code;
		public $address;

		// This is constructor we have to pass connection of the database

		public function __construct($connection)
		{
        	$this->connection = $connection;
    	}

		//This function will perform insert operation for User and User Address tables.

		public function ins_user() 
		{
			// Importing required libraries for Phone Number validations.
			require 'vendor/autoload.php';
			$phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
			$phoneNumberObject = $phoneNumberUtil->parse($this->user_phone, 'GB');
			$isValid = $phoneNumberUtil->isValidNumber($phoneNumberObject);
			// Checking Phone Number is valid or not. If it is not valid then function will return 1 as a failure.
			if($isValid !=1)
			{
				return 1;
			}
			$enc_pass = password_hash($this->user_password,PASSWORD_DEFAULT);
			// Validating email address
			// $api_res = $this->CallAPI("GET","https://tls.bankaccountchecker.com/listener.php?key=fd9c581a6d9faaef963994f61f842d92&password=Thisisnewpass@88&output=json&type=email&email=".$this->user_email);
			// $api_res = json_decode($api_res);
			// // Checking Email Address is valid or not. If it is not valid then function will return 2 as a failure.
			// if($api_res->resultCode != "01")
			// {
			// 	return 2;
			// }

			// This will insert data into User table in database.
			$sql = "INSERT INTO user (user_name,user_password,user_email,user_phone,age,registration_date) values(:user_name,:user_password,:user_email, :user_phone,:age,NOW())";			
			$stmt = $this->connection->prepare($sql);
			$stmt->bindParam(":user_name", $this->user_name, PDO::PARAM_STR);
			$stmt->bindParam(":user_email", $this->user_email, PDO::PARAM_STR);
			$stmt->bindParam(":user_password", $enc_pass, PDO::PARAM_STR);					
			$stmt->bindParam(":user_phone", $this->user_phone, PDO::PARAM_STR);
			$stmt->bindParam(":age", $this->age, PDO::PARAM_STR);
			$stmt->execute();			

			// Retriving latest user id to use as a foreign key value for user address table.
			$sql = "SELECT user_id FROM user order by 1 desc limit 1";
			$stmt = $this->connection->prepare($sql);			
			$stmt->execute();
			$row = $stmt->fetchAll(PDO::FETCH_OBJ);
			$userid = $row[0]->user_id;

			// This will insert data into User Address table in database.
			$sql = "INSERT INTO user_address (user_id,city,post_code,address) values(:user_id,:city,:post_code,:address)";
			$stmt = $this->connection->prepare($sql);
			$stmt->bindParam(":user_id", $userid, PDO::PARAM_STR);
			$stmt->bindParam(":city", $this->city, PDO::PARAM_STR);
			$stmt->bindParam(":post_code", $this->post_code, PDO::PARAM_STR);
			$stmt->bindParam(":address", $this->address, PDO::PARAM_STR);
			$stmt->execute();
			return 0;
		}

		// This will update the data for the user
		public function update_user()
		{

			// Importing required libraries for Phone Number validations.
			require 'vendor/autoload.php';
			$phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
			$phoneNumberObject = $phoneNumberUtil->parse($this->user_phone, 'GB');
			$isValid = $phoneNumberUtil->isValidNumber($phoneNumberObject);
			// Checking Phone Number is valid or not. If it is not valid then function will return 1 as a failure.
			if($isValid !=1)
			{
				return 1;
			}

			// This will update the data in user table.
			$sql = "update user set user_name= :user_name,
			user_email= :user_email,user_phone =:user_phone,age= :age
			WHERE user_id= :user_id";			
			$stmt = $this->connection->prepare($sql);
			$stmt->bindParam(":user_name", $this->user_name, PDO::PARAM_STR);
			$stmt->bindParam(":user_email", $this->user_email, PDO::PARAM_STR);
			$stmt->bindParam(":user_phone", $this->user_phone, PDO::PARAM_STR);
			$stmt->bindParam(":age", $this->age, PDO::PARAM_STR);
			$stmt->bindParam(":user_id", $this->user_id, PDO::PARAM_STR);	
			$stmt->execute();

			// This will update the data in user address table.
			$sql = "update user_address set city= :city,
			post_code= :post_code,address= :address
			WHERE user_id= :user_id";			
			$stmt = $this->connection->prepare($sql);
			$stmt->bindParam(":city", $this->city, PDO::PARAM_STR);
			$stmt->bindParam(":post_code", $this->post_code, PDO::PARAM_STR);
			$stmt->bindParam(":address", $this->address, PDO::PARAM_STR);
			$stmt->bindParam(":user_id", $this->user_id, PDO::PARAM_STR);
			$stmt->execute();
			return 0;
		}		

		// This function will be used to check username and password for log in functionality.
		public function login()
		{
			// $enc_pass = password_hash($this->user_password,PASSWORD_DEFAULT);
			$sql = "SELECT user_id,user_name,user_password FROM user WHERE user_email = :user_email";
			// $sql = "SELECT user_id,user_name FROM user WHERE user_email = :user_email and user_password = :user_password";
			$stmt = $this->connection->prepare($sql);	
			$stmt->bindParam(":user_email", $this->user_email, PDO::PARAM_STR);
			// $stmt->bindParam(":user_password", $enc_pass, PDO::PARAM_STR);		
			$stmt->execute();
			$row = $stmt->fetchAll(PDO::FETCH_OBJ);


		      // If result matched email and password, table must have 1 row.

		      if(count($row) == 1)
		      {
					if(password_verify($this->user_password,$row[0]->user_password))
					{
						$datadict->status="Success";
						$datadict->user_id = $row[0]->user_id;
						$datadict->user_name = $row[0]->user_name;
					}
					else
					{
						$datadict->status="Fail";
						$datadict->user_id = 0;
						$datadict->user_name = "NA";
					}
		      		
		      }
		      else
		      {
				$datadict->status="Fail";
				$datadict->user_id = 0;
				$datadict->user_name = "NA";
		      }			 
			  return $datadict;
		}

		// This function is used to get dashboard data. 
		public function user_view()
		{
			$sql = "SELECT a.user_id,a.user_name,a.user_password,a.user_email,a.user_phone,a.age,a.registration_date,b.city,b.post_code,b.address FROM user a
			INNER JOIN user_address b on b.user_id=a.user_id";
			$stmt = $this->connection->prepare($sql);			
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_OBJ);

          $data = array();
		      if(count($result) > 0)
		      {

			 foreach ($result as $row1)  {
				$temp_data = array();
				// Here we are generating URL to redirect on update user page.
				array_push($temp_data,"<a href='updatedata.html?id=". $row1->user_id ."' />". $row1->user_id . "</a>");
				array_push($temp_data,$row1->user_name);
				array_push($temp_data,$row1->user_email);
				array_push($temp_data,$row1->user_phone);
				array_push($temp_data,$row1->age);
				array_push($temp_data,$row1->registration_date);
				array_push($temp_data,$row1->address);
				array_push($temp_data,$row1->city);
				array_push($temp_data,$row1->post_code);
                array_push($data,$temp_data);
			}
              
		      }
		      else
		      {
		         	$datadict->status="Fail";
		      		$datadict->userid = 0;
		      		$datadict->username = "NA";
              array_push($data,$datadict);
		      }
		      return $data;
		}

		// This function is used to get user data by user id.
		public function get_user_data_byid()
		{
			$sql = "SELECT a.user_id,a.user_name,a.user_password,a.user_email,a.user_phone,a.age,a.registration_date,b.city,b.post_code,b.address FROM user a
			INNER JOIN user_address b on b.user_id=a.user_id
			where a.user_id=:user_id";
			$stmt = $this->connection->prepare($sql);	
			$stmt->bindParam(":user_id", $this->user_id, PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_OBJ);

		$data = array();
			if(count($result) > 0)
			{
			foreach ($result as $row1)  {
				$datadict->user_id=$row1->user_id;
				$datadict->user_name=$row1->user_name;
				$datadict->user_email=$row1->user_email;
				$datadict->user_phone=$row1->user_phone;
				$datadict->age=$row1->age;
				$datadict->city=$row1->city;
				$datadict->address=$row1->address;
				$datadict->post_code=$row1->post_code;
				array_push($data,$datadict);
			}
			}
			else
			{
					$datadict->status="Fail";
					$datadict->userid = 0;
					$datadict->username = "NA";
			array_push($data,$datadict);
			}

			return $data;
		}

		// This is a generic function to call API an retrive the respone from API. 
		function CallAPI($method, $url, $data = false)
		{
			$curl = curl_init();

			switch ($method)
			{
				case "POST":
					curl_setopt($curl, CURLOPT_POST, 1);

					if ($data)
						curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
					break;
				case "PUT":
					curl_setopt($curl, CURLOPT_PUT, 1);
					break;
				default:
					if ($data)
						$url = sprintf("%s?%s", $url, http_build_query($data));
			}

			// Optional Authentication:
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($curl, CURLOPT_USERPWD, "username:password");

			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

			$result = curl_exec($curl);

			curl_close($curl);

			return $result;
		}
	}
