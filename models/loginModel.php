<?php
// include_once '../spConnection/SPConnection.php';
// include_once ("../spConnection/SPSelectDatabase.php");
// include_once ("commonModel.php");
/**
 * *********Database connection**********************
 */

class loginModel  {
	public $objDatabase;
	function __construct() {
		$servername = "localhost";
		$username = "root";
		$password = "";
		$conn = new PDO("mysql:host=$servername;dbname=business", $username, $password);
			// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->objDatabase = $conn;
	}
	
	public function create_user($data_array) {
		$result['status'] = FALSE;
		$result['data'] = '';
		try {
				$sql = "INSERT INTO `user_registration`(`first_name`, `last_name`, `user_type`, `mobile_number`, `e_mail`, `password`) 
				VALUES (:FIRST_NAME,:LAST_NAME,:USER_TYPE,:MOBILE_NUMBER,:E_MAIL,md5(:PASSWORD))";
				// use exec() because no results are returned
				$query = $this->objDatabase->prepare ( $sql );
				$query->bindParam ( ':FIRST_NAME', $data_array['first_name'] );
				$query->bindParam ( ':LAST_NAME', $data_array['last_name'] );
				$query->bindParam ( ':USER_TYPE', $data_array['user_type'] );
				$query->bindParam ( ':MOBILE_NUMBER', $data_array['mobile_number'] );
				$query->bindParam ( ':E_MAIL', $data_array['e_mail'] );
				$query->bindParam ( ':PASSWORD', $data_array['password'] );
				$success = $query->execute ();
				
				if($success){
					$result['status'] = TRUE;
					$result['data'] = $this->objDatabase->lastInsertId();
				}
			
			}
	    catch(PDOException $e)
		{
			$result['data'] =  $e->getMessage();
		}
		return $result;
	}
	
	public function read_user($data_array) {
		$result['status'] = FALSE;
		$result['data'] = '';
		try {
			$stmt = 'SELECT *
			 FROM `user_registration` 
			 WHERE ';
			
			if( isset($data_array['first_name']) && $data_array['first_name'] != NULL){
				$stmt .='  `first_name`=:FIRST_NAME AND';
			}			
			if( isset($data_array['last_name']) && $data_array['last_name'] != NULL){
				$stmt .=' `last_name`=:LAST_NAME AND';
			}
			if( isset($data_array['mobile_number']) && $data_array['mobile_number'] != NULL){
				$stmt .=' `mobile_number`=:MOBILE_NUMBER AND';
			}
			if( isset($data_array['e_mail']) && $data_array['e_mail'] != NULL){
				$stmt .=' `e_mail`=:E_MAIL AND';
			}
			if( isset($data_array['user_type']) && $data_array['user_type'] != NULL){
				$stmt .=' `user_type`=:USER_TYPE AND';
			}
			if( isset($data_array['password']) && $data_array['password'] != NULL){
				$stmt .=' `password`= md5(:PASSWORD) AND';
			}
			$stmt .=' 1';
			$query = $this->objDatabase->prepare ( $stmt );
			
			if( isset($data_array['first_name']) && $data_array['first_name'] != NULL){
				$query->bindParam ( ':FIRST_NAME', $data_array['first_name'] );
			}
			if( isset($data_array['last_name']) && $data_array['last_name'] != NULL){
				$query->bindParam ( ':LAST_NAME', $data_array['last_name'] );
			}
			if( isset($data_array['mobile_number']) && $data_array['mobile_number'] != NULL){
				$query->bindParam ( ':MOBILE_NUMBER', $data_array['mobile_number'] );
			}
			if( isset($data_array['e_mail']) && $data_array['e_mail'] != NULL){
				$query->bindParam ( ':E_MAIL', $data_array['e_mail'] );
			}
			if( isset($data_array['user_type']) && $data_array['user_type'] != NULL){
				$query->bindParam ( ':USER_TYPE', $data_array['user_type'] );
			}
			if( isset($data_array['password']) && $data_array['password'] != NULL){
				$query->bindParam ( ':PASSWORD', $data_array['password'] );
			}
			$query->execute ();
			$result_data = $query->fetchAll ( PDO::FETCH_ASSOC );
			$result ['status'] = TRUE;
			$result ['data'] = ($result_data) ? $result_data : NULL;
			
		}
	    catch(PDOException $e)
		{
			$result ['status'] = FALSE;
			$result['data'] =  $e->getMessage();
		}
	
		return $result;
	}
	
	
	
}