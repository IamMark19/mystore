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

    public function show_404(){
        http_response_code(404);
        echo "Page Not found";
        die;
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
    public function get_single_product($id)
    {
        $connection= $this->openConnection();
        $stmt= $connection->prepare("SELECT t1.id, product_name,product_type,
        min_stock, SUM(qty) as total FROM 
        (SELECT * FROM products WHERE products.id =?) t1 INNER JOIN product_items t2 ON t1.id=t2.product_id ");
        $stmt->execute([$id]);
        $product = $stmt->fetch();
        $total = $stmt->rowCount(); 
        if($total > 0){
            return $product;
        }else{
            return $this->show_404();
        }   
    }
    // Other OFtion total Quantity
    // public function get_total_qty($product_id)
    // {
    //     $connection= $this->openConnection();
    //     $stmt= $connection->prepare("SELECT *, SUM(qty) as total FROM product_items WHERE product_id=? ");
    //     $stmt->execute([$product_id]);
    //     $product_qty = $stmt->fetch();
    //     return $product_qty['total'];
    // }
    public function add_stock()
    {
        if(isset($_POST['add_stock'])){
            $brand_name = $_POST['brand_name'];
            $qty =$_POST['qty'];
            $batch_number = $_POST['batch_number'];
            $product_id = $_POST['product_id'];
            $added_by =$_POST['added_by'];
            $price = $_POST['price'];
            $connection= $this->openConnection();
            $stmt = $connection->prepare("INSERT INTO product_items(`product_id`,`qty`,`price`,`Vendor_name`,`added_by`,`batch_number`) VALUES (?,?,?,?,?,?)");
            $stmt->execute([$product_id,$qty,$price,$brand_name,$added_by, $batch_number]);
            header("location: product_details.php?id=".$product_id);
        }
    }
    public function view_all_stocks($product_id)
    {
        $connection= $this->openConnection();
        //$stmt= $connection->prepare("SELECT * FROM product_items where product_id = ? ");
        $stmt= $connection->prepare("SELECT t1.id, t1.vendor_name, t1.price, t1.qty, SUM(t2.qty) as sales_qty, SUM(t2.qty * t2.price) as TotalSales  FROM product_items t1 LEFT JOIN sales t2 ON t1.id=t2.stock_id WHERE t1.product_id=? GROUP BY t1.id");
        $stmt->execute([$product_id]);
        $stocks = $stmt->fetchAll();
        $total = $stmt->rowCount(); 
        if($total > 0){
            return $stocks;
        }else{
            return False;
        }
    }
    public function get_stock_details($stock_id)
    {  
        $connection= $this->openConnection();
        $stmt= $connection->prepare("SELECT * FROM product_items where id = ? ");
        $stmt->execute([$stock_id]);
        $stocks = $stmt->fetch();
        $total = $stmt->rowCount(); 
        if($total > 0){
            return $stocks;
        }else{
            return $this->show_404();
        }
    }
    public function insert_sales($stock_id, $qty, $price, $product_id,$customer_name)
    {
        $item = $this->get_stock_details($stock_id);
        $brand=$item['vendor_name'];
        $connection= $this->openConnection();
        $stmt= $connection->prepare("INSERT INTO sales (`product_id`, `stock_id`, 
        `brand name`, `qty`, `price`, `customer_name`) VALUES(?,?,?,?,?,?) ");
        $stmt->execute([$product_id,$stock_id,$brand,$qty,$price,$customer_name]);
      
        
    }
}
$store= new Mystore(); 


