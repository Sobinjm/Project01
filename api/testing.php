<?php
class Database{
    private $host="localhost";
    private $db_name="listing";
    private $username="root";
    private $password="";
    public $conn;
    public function connect()
    {
        // $this->conn=null;
        try{
            $servername = "localhost";
		    $username = "root";
            $password = "";
            $dbname="listing";
		    $this->conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            return $this->conn;
        }
        catch(PDOException $e)
        {
            echo 'Connection Error'.$e->getMessage();
        }
        
    }
}
class Users{
    public $conn;
    private $main_table="user_details";

    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $mobile_number;
    public $user_type;
    public $password;
    public $account_status;
    public $created_at;
    public $updated_at;
    public $login_status;

    function __construc($db)
    {
        $this->conn=$db;
        // die('sdfsdfs');
        
    }

    public function loginCheck()
    {
        try{
            
        $query="SELECT * FROM user_details where email=? ";
        $stmt=$this->conn->prepare ($query);

        $stmt->bindParam(1,$this->email);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        $this->first_name=$row['first_name'];
        $this->last_name=$row['last_name'];
        $this->email=$row['email'];
        $this->mobile_number=$row['mobile_number'];
        $this->user_type=$row['user_type'];
        $this->account_status=$row['account_status'];
        }catch(PDOException $e)
        {
            echo 'Connection Error'.$e->getMessage();
        }

    }

    

}






?>