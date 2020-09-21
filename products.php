<?php 
require_once ('storeclass.php');
$products=$store->get_product();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <ul>
    <?php foreach ($products as $product) { ?>
        <li><a href="product_details.php?id=<?= $product['id'];?>"><?= $product['product_name'];?>|<?= $product['min_stock'];?></a></li>
    <?php }?>
    </ul>
</body>
</html>