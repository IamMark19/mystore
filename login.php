<?php require_once('storeclass.php'); 
$store->login();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
     <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
     <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
     <script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <form action="" method="post">
                <div class="form-input">
                    <labe>Username</label>
                    <input type="text" name="email" id="email">
                </div>

                <div class="form-input">   
                    <labe>Password.</label>
                    <input type="password" name="password" id="password">
                </div>  
                <button type="submit" name="submit">Login</button>
            </form>
        
        
        </div>
    </div>
</body>
</html>