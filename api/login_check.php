<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include_once '../models/user.php';
include_once '../config/Database.php';


$database=new Database();
$db=$database->connect();
$user=new Users($db);
$user->email=isset($_GET['email']) ? $_GET['email']:die();
$user->password=isset($_GET['pwd']) ? $_GET['pwd']:die();
$result =$user->loginCheck();

$user_arr=array(
    'id'=>$user->id,
    'first_name'=>$user->first_name,
    'last_name'=>$user->last_name,
    'mobile_number'=>$user->mobile_number,
    'user_type'=>$user->user_type,
    'account_status'=>$user->account_status,
    'email'=>$user->email
    
);

// print_r($user_arr);
print_r(json_encode($user_arr));

?>