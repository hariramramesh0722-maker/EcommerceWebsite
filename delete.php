<?php

$conn = mysqli_connect("localhost","root","","project");

$id = $_GET['id'];

// get image name
$result = mysqli_query($conn,"SELECT image FROM product WHERE id=$id");
$row = mysqli_fetch_assoc($result);

$image = $row['image'];

// delete image file
unlink("uploads/".$image);

// delete product from database
mysqli_query($conn,"DELETE FROM product WHERE id=$id");

// redirect back
header("Location: inde.php");

?>