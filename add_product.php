<?php

$name = $_POST['name'];
$image = $_POST['image'];
$page = $_POST['page'];

$product = "

<div class='imgcard'>
<center>$name</center>
<hr>
<img class='img1' src='$image'>
<a href='$page'>
<button class='card2'>Buy now</button>
</a>
</div>

";

file_put_contents("products.html",$product,FILE_APPEND);

echo "Product Added";

?>