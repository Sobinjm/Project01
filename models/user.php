<?php

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

    public function __construct($db)
    {
        $this->conn=$db;
        
    }

    public function loginCheck()
    {
        try{
            
                $query="SELECT * FROM user_details where email=:EMAIL ";
                $stmt=$this->conn->prepare ($query);
                $stmt->bindParam(':EMAIL',$this->email);
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


    public function selectUser($id)
    {
        try{
                $query="SELECT * FROM user_details where id=:ID ";
                $stmt=$this->conn->prepare ($query);
                $stmt->bindParam(':ID',$id);
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