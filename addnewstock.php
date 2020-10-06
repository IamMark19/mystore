<?php
require_once ('storeclass.php');
$id=$_GET['id'];
$product=$store->get_single_product($id);
$userdetails =$store->get_userdata();
$store->add_stock($_POST);
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
    <label >Brand name</label>
    <input type="text" name="brand_name" id="brand_name" require>
    <label >Qty</label>
    <input type="number" name="qty" id="qty"  min="1" value="1" require>
    <label >Price</label>
    <input type="number" name="price" id="price"  min="1" value="1" require>
    <label >Brand Number</label>
    <input type="text" name="brand_number" id="brand_number" require>
    <input type="hidden" name="product_id" value="<?= $product['id'];?>">
    <input type="hidden" name="added_by" id="added_by" value="<?=$userdetails['fullname']; ?>">
    <button type="submit" name="add_stock">Add Stock</button>
    </form>
</body>
</html>