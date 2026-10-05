<?php

$conn = mysqli_connect("localhost","root","","project");

if(isset($_POST['submit']))
{

$product_name = $_POST['product_name'];
$price = $_POST['price'];
$carddetails = $_POST['carddetails'];
$details = $_POST['details'];
$name= $_POST['name'];

$image = $_FILES['image']['name'];
$temp = $_FILES['image']['tmp_name'];

$newname = time()."_".$image;
$folder = "uploads/".$newname;

if(!is_dir("uploads"))
{
mkdir("uploads",0777,true);
}

if(move_uploaded_file($temp,$folder))
{

$query = "INSERT INTO product(product_name,price,carddetails,details,image,name)
VALUES('$product_name','$price','$carddetails','$details','$newname','$name')";

mysqli_query($conn,$query);

/* stay on admin page */
header("Location: inde.php");
exit;

}

else
{
echo "Image upload failed";
}

}

?>