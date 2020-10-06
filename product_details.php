<?php
require_once ('storeclass.php');
$id=$_GET['id'];
$product=$store->get_single_product($id);
$stocks=$store->view_all_stocks($id);
$userdetails =$store->get_userdata();
$inventory_array=array();
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
     <link rel="stylesheet" href="css/style.css">
     <title>Document</title>
 </head>
 <body>
     <h1><?= $product['product_name']; ?></h1>
     <h2>Category : <?= $product['product_type']; ?></h2>
     <h3>Min Stock : <?= $product['min_stock']; ?></h3></br>
     
<hr>
<h2>available product items</h2>
<table border="1">
    <thead>
        <tr>
         <th>Action</th>
         <th>Base Stock Qty</th>
         <th>SRP</th>
         <th>Sales qty</th>
         <th>Total Sales</th>
         <th>qty Remaining</th>
         <th>Status</th>
        </tr>
    </thead>
    <tbody>
    <?php if(is_array($stocks)){
     foreach($stocks as $stock){ 
     $sum= $stock['qty'] - $stock['sales_qty'];
     $inventory_array[]= $sum;?>
    <tr class=" <?=($sum ==0)? 'disabledbtn':''?>">
        <td>
       
                <div id="parent_<?= $stock['id'];?>">
                    <label><?= $stock['vendor_name'];?> <?= $stock['qty'];?> </label>
                
                    <input type="number" name="qty[] " min="1" max="<?=$sum;?>" value="1">
                    <input type="hidden" name="price[]" value="<?=$stock['price']?>" id="">
                    <input type="hidden" name="stock_id[]" value="<?=$stock['id'];?>">
                    <button type="button" class="add_cart">Add to Cart </button>
                    <button type="button" class="remove_cart" id="<?= $stock['id'];?>" disabled>Removed </button>
                </div>
           
        </td>
        <td><?=$stock['qty'];?></td>
        <td><?=sprintf('%01.2f',$stock['price']);?></td>
        <td><?=$stock['sales_qty'];?></td>
        <td><?=sprintf('%01.2f',$stock['TotalSales']);?></td>
        <td><?=$sum?></td>
        <td>
        <?=($sum ==0)? 'Out of Stock':'Available'?>
        </td>
        
     </tr>
     <?php } } ?>     
    </tbody>
</table>
<h4>Total Inventory : <?= $product['total'];?> </h4>
<h4>Actual Inventory : <?=array_sum($inventory_array);?></h4>
<h4>Status :
<?php 
if (array_sum($inventory_array)<=$product['min_stock']) {
    echo "Low Stock";
}elseif(array_sum($inventory_array)==0){
    echo "out of stock";
}else{
    echo "High Stock";
}


?>

</h4>
<p></p>
     <a href="products.php">Products</a>
     <a href="addnewstock.php?id=<?=$product['id']?>">Add new Stocks</a>
 <hr>
 <h2>Cart</h2>
 <form action="checkout.php" method="post" id="check_out_form">
<input type="hidden" name="customer_name" value="<?=$userdetails['fullname']; ?>">
 <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
 <button type="submit" id="checkoutbtn">Proceed to check out</button>
 
 </form>
 <script src="js/index.js"></script>
 </body>
 </html>