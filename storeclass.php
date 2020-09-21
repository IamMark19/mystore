<?php
class Mystore{
    private $server ="mysql:host=localhost;dbname=mystore";
    private $user ="root";
    private $password ="target12191997";
    private $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
    protected $con;

    public function openConnection(){

        try {
           $this->con = new PDO($this->server,$this->user,$this->password,$this->options);
           return $this->con;
        } catch (PDOExcemption  $e) {
            echo "There is a problem in the connection :".$e->getMessage();
        }
    }
    public function closeConnection(){
        $this->con = null;
    }
    public function getUsers(){
        $connection =$this->openConnection();
        $stmt = $connection->prepare("SELECT * From members");
        $stmt->execute();
        $users = $stmt->fetchAll(); 
        $userCount =$stmt->rowCount();
        if($userCount >0){
            return $users;
        }else{
            return 0;
        }
    }
    //for login
    public function login(){
        
        
        if(isset($_POST['submit']))
        {
           
           
          
            $password = md5($_POST['password']);
            $username =$_POST['email'] ;
            
   
                
                $connection= $this->openConnection();
                $stmt = $connection->prepare("SELECT * FROM members WHERE email=? AND password=? ");
                $stmt->execute([$username,$password]);
               // to get the data on the database
                $user = $stmt->fetch();
                $total = $stmt->rowCount();
                if ($total > 0){
                    echo "welcome ".$user['first_name']." ".$user['last_name'];
                    $this->set_userdata($user);
                }else{
                    echo "login failed";
                }
           
        }
    }
    //for session
    public function set_userdata($array)
    {
        if(!isset($_SESSION)){
            session_start(); 
        }
        $_SESSION['userdata'] = array(
            "fullname" => $array ['first_name']." ".$array['last_name'],
            "access" => $array['access']      
        );

        return $_SESSION['userdata'];
    }

    public function get_userdata(){
        if(!isset($_SESSION)){
            session_start(); 
            
        }
        if(isset($_SESSION['userdata'])){
        return $_SESSION['userdata'];
        }else{
            return null;
        }
    }

    public function logout(){
        if(!isset($_SESSION)){
            session_start(); 
            
        }
        $_SESSION['userdata']= null;
        unset( $_SESSION['userdata']);
    }
 
    //check user existence
    public function check_user_exist($email){
        $connection= $this->openConnection();
                $stmt = $connection->prepare("SELECT * FROM members WHERE email=?  ");
                $stmt->execute([$email]);
                $total = $stmt->rowCount();
                return $total;
    }
    //add user
    public function add_user(){
       
        if(isset($_POST['add'])){
            $email = $_POST['email'];
            $password = md5($_POST['password']);
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];

            if($this->check_user_exist($email) == 0){
            
            $connection= $this->openConnection();
            $stmt = $connection->prepare("INSERT INTO members(`email`,`password`,`first_name`,`last_name`)VALUES(?,?,?,?) ");
            $stmt->execute([$email,$password,$fname,$lname]);
            }else{
                echo "User already Exist";
            }       
        }


    }
    public function check_product_exist($name){
            
            $connection= $this->openConnection();
            $stmt = $connection->prepare("SELECT LOWER(`product_name`) FROM products WHERE product_name = ? ");
            $stmt->execute([strtolower($name)]);
            $total = $stmt->rowCount();
            return $total;
           
       
    }
    public function add_product()
    {
        if(isset($_POST['add_product'])){
            $product_name =$_POST['product_name'];
            $product_type =$_POST['product_type'];
            $min_stock = $_POST['min_stock'];

            if($this->check_product_exist($product_name) == 0)
            {
            $connection= $this->openConnection();
            $stmt = $connection->prepare("INSERT INTO products(`product_name`,`product_type`,`min_stock`) 
            VALUES(?, ?, ?)");
            $stmt->execute([$product_name,$product_type,$min_stock]);
            echo "added successfully";
            }else{
                echo "Product already Exist";
            }
        }
    }
    public function get_product(){
        $connection= $this->openConnection();
        $stmt= $connection->prepare("SELECT * FROM products");
        $stmt->execute();
        $products = $stmt->fetchAll();
        $total = $stmt->rowCount();
        if($total > 0){
            return $products;
        }else{
            return false;
        }

    }
 }
 $store= new Mystore(); 


