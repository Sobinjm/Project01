<?php

include_once '../models/loginModel.php';
$_POST = json_decode ( file_get_contents ( 'php://input' ) );

if(isset ( $_POST ) && !empty($_POST)){
	$json_decoded = $_POST->data;
	$mode	= $json_decoded->mode;
}  else if (isset ( $_GET ['data'] )) {
	$json_object = $_GET ['data'];
	$json_decoded = json_decode ( $json_object );
	$mode = $json_decoded->mode;
} else {
	$status = FALSE;
	$msg = 'Check the data/method in your request';
	displayResponse ( $status, $msg );
}

handleApiRequest ( $json_decoded );

/*
 * Arun At 2017-07-26
 * @func : handleApiRequest
 * @params : STRING,ARRAY
 * @return : VOID
 * @desc : function to check the API request data and create the instances.
 */
function handleApiRequest($json_decoded) {

	$modelObj = new loginModel ();
	$controllerObj = new projectController ( $modelObj );
	$controllerObj->processRequest ( $json_decoded );
	
}

function displayResponse($status, $msgOrData) {
	$response = [ ];
	$response ['status'] = $status;
	$response ['result'] = (! empty ( $msgOrData ) && $msgOrData != NULL) ? $msgOrData : "";
	
	$return_array ['rootlogin'] [0] = $response;
	$jsonString = json_encode ( $return_array );
	echo $jsonString;
	exit (1);
}

class projectController {
	private $modelInstance;
	function __construct($modelObj) {
		$this->modelInstance = $modelObj;
	}
	
	public function processRequest($dataObject) {
		if (! empty ( $dataObject ) && isset ( $dataObject->mode ) && ! empty ( $dataObject->mode )) {
			$action = $dataObject->mode;
			if (method_exists ( $this, $action )) {
				$return = call_user_func ( array (
						$this,
						$action 
				), $dataObject );
				displayResponse ( $return ['status'], $return ['data'] );
			} else {
				$status = FALSE;
				$msg = 'Method not found.Check mode';
				displayResponse ( $status, $msg );
			}
		} else {
			$status = FALSE;
			$msg = 'mode missing.Check input';
			displayResponse ( $status, $msg );
		}
	}

	
	public function register_user($dataObject) {
		$return_array['data'] = [];
		$return_array['status'] = FALSE;

		$date_array['first_name'] = $dataObject->first_name;
		$date_array['last_name'] = $dataObject->last_name;
		$date_array['mobile_number'] = $dataObject->mobile_number;
		$date_array['e_mail'] = $dataObject->e_mail;
		$date_array['password'] = $dataObject->password;
		$date_array['confirm_password'] = $dataObject->confirm_password;
		$date_array['user_type'] = $dataObject->user_type;
		
		if(empty($date_array['first_name']))  { $return_array['data']['message'] = 'First Name is required '; 	goto sendres;}
		
		else if(empty($date_array['last_name']))	{ $return_array['data']['message'] = 'Last Name is required '; 	goto sendres;}
		
		else if(empty($date_array['mobile_number']))	{ $return_array['data']['message'] = 'Mobile Number is required '; 	goto sendres;}
		
		else if(empty($date_array['confirm_password']))	{ $return_array['data']['message'] = 'Confirm Password is required '; 	goto sendres;}

		else if(empty($date_array['e_mail']))	{ $return_array['data']['message'] = 'Email Address is required '; 	goto sendres;}

		else if(empty($date_array['user_type']))	{ $return_array['data']['message'] = 'User Type is required ';	goto sendres;}
		
		else if(empty($date_array['password']))	{ $return_array['data']['message'] = 'Password is required '; 	goto sendres;}
		
		
		else if($date_array['password'] !== $date_array['confirm_password']) { $return_array['data']['message'] = 'Password and Confirm Password should be same '; 	goto sendres;}
		
		else{
			$duplicate_array['e_mail'] = $date_array['e_mail'];
			$duplicate_user = $this->modelInstance->read_user($duplicate_array);
		
			if(!isset($duplicate_user['data']) && !is_array($duplicate_user['data'])){
				$result = $this->modelInstance->create_user($date_array);
				
				if($result['status'] && !empty($result['data'])){
					$return_array['data']['message'] = 'User registered Successfully';
					$return_array['data']['last_insert_id'] = $result['data'];
					$return_array['status'] = TRUE;
				} else {
					$return_array['data']['message'] = 'Something went wrong';
					$return_array['data']['last_insert_id'] = NULL;
					$return_array['status'] = FALSE;
				}
			} else{
				$return_array['data']['message'] = 'Trying to Register Duplicate User.';
				$return_array['data']['last_insert_id'] = NULL;
				$return_array['status'] = FALSE;
			}
			goto sendres;
		}
		
		sendres:
		return $return_array;
		
	}
	
	public function login_user($dataObject) {
		$return_array['data'] = [];
		$return_array['status'] = FALSE;

		$date_array['e_mail'] = $dataObject->e_mail;
		$date_array['password'] = $dataObject->password;
		
		if(empty($date_array['e_mail']))	{ $return_array['data']['message'] = 'Email Address is required '; 	goto sendres;}

		else if(empty($date_array['password']))	{ $return_array['data']['message'] = 'Password is required '; 	goto sendres;}
		
		else{
			
			$result = $this->modelInstance->read_user($date_array);
			
			if($result['status'] && !empty($result['data'])){
				$return_array['data']['message'] = 'Login Successfully.Start Session For Web';
				$return_array['data']['user_id'] = ($result['data'][0]['id']) ? $result['data'][0]['id'] : NULL ;
				$return_array['data']['user_type'] = ($result['data'][0]['user_type']) ? $result['data'][0]['user_type'] : "NIA" ;
				$return_array['data']['name'] = ($result['data'][0]['first_name']) ? $result['data'][0]['first_name']." ".$result['data'][0]['last_name'] : "NIA";
				$return_array['status'] = TRUE;
			} else {
				$return_array['data']['message'] = 'Inavlid Username and Password';
				$return_array['data']['user_id'] = NULL;
				$return_array['data']['user_type'] = NULL;
				$return_array['data']['name'] = NULL;
				$return_array['status'] = FALSE;
			}
			
			goto sendres;
		}
		
		sendres:
		return $return_array;
		
	}
}
	
