<?php require_once ('storeclass.php'); 
$store->add_user();
$userdetails =$store->get_userdata();
if(isset($userdetails)){
    if($userdetails['access']!="administrator"){
        header("location:login.php");
    } 

}else{
    header("location:login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Add New Customer/user</h1>
    <div class="container">
        <div class="form-container">
            <form action="" method="post">
                <div class="form-input">
                    <labe>Email</label>
                    <input type="text" name="email" id="email">
                </div>

                <div class="form-input">   
                    <labe>Password.</label>
                    <input type="password" name="password" id="password">
                </div>  
                
                <div class="form-input">
                    <labe>First Name</label>
                    <input type="text" name="fname" id="fname">
                </div>

                <div class="form-input">
                    <labe>Last Name</label>
                    <input type="text" name="lname" id="lname">
                </div>

                <button type="submit" name="add">add user</button>
           
            </form>
          </div>
    </div>
</body>
</html>