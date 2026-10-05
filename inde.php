<?php
$conn = mysqli_connect("localhost","root","","project");

if(!$conn)
{
    die("Database connection failed");
}

?>

<!DOCTYPE html>
<html>
<head>
    <style>
body{
    font-family: Arial, sans-serif;
    background-color:#f4f6f9;
}

/* Table container */
table{
    border-collapse: collapse;
    width: 90%;
    margin: 40px auto;
    background-color: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

/* Table header */
th{
    background-color: #ff3b3b;
    color: white;
    padding: 12px;
    text-align: center;
}

/* Table rows */
td{
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

/* Alternate row color */
tr:nth-child(even){
    background-color: #f9f9f9;
}

/* Hover effect */
tr:hover{
    background-color: #f1f1f1;
}

/* Image styling */
td img{
    border-radius: 6px;
}

/* Delete button */
button{
    background-color: red;
    color: white;
    border: none;
    padding: 8px 14px;
    cursor: pointer;
    border-radius: 4px;
    font-size: 14px;
}
.logout{
    margin-left: 1100px; 
    text-decoration: none;

}

button:hover{
    background-color: darkred;
}
.logout {
            width: 100px;
            height: 35px;
            color: white;
            background-color: red;
            border-radius: 7px;
            border: white;
            margin-left: 1200px;

        }

</style>
<title>ElectroZone Admin</title>
<link rel="stylesheet" href="add.css">
</head>

<body>
   
<h2 style="display:inline-block;color:red;">E</h2>
    <h3 style="display:inline-block;margin-left:0px;">lectroZone.in</h3>
  <br>
    <button class="logout">
        <a style="color:white;text-decoration: none;" href="Admin.php">Back</a></button>
<center>

<h1><span style="color:red;"></span> Admin Panel</h1>

<br>

<h2>Add Product</h2>

<form action="upload.php" method="POST" enctype="multipart/form-data">

<label>Product Name</label><br>
<input type="text" name="product_name" required>

<br><br>

<label>Price</label><br>
<input type="text" name="price" required>

<br><br>

<label>Product card details</label><br>
<input type="text" name="carddetails" required>

<br><br>

<label>Product Details</label><br>
<textarea name="details" rows="4" cols="40"></textarea>
<br>
<br>
<center>
<label>Product Image</label><br>
<br>
<input style="margin-left:120px;"type="file" name="image" required>
</center>
<br>
<label >Name</label><br>
<input style="margin-left:10px;"  type="text" name="name">
<br><br><br>
<input style="background-color:red;color:white;height:35px;border:1px white;" type="submit" name="submit" value="Add Product">

</form>

<br><br><br>

<h2>Product List</h2>

<table border="1" cellpadding="10">

<tr>
<th>ID</th>
<th>Image</th>
<th>Name</th>
<th>Price</th>
<th>Quantity</th>
<th>Details</th>
<th>Action</th>
</tr>

<?php

$result = mysqli_query($conn,"SELECT * FROM product ORDER BY id DESC");

while($row = mysqli_fetch_assoc($result))
{
?>

<tr>

<td><?php echo $row['id']; ?></td>

<td>
<img src="uploads/<?php echo $row['image']; ?>" width="120">
</td>

<td><?php echo $row['product_name']; ?></td>

<td>₹<?php echo $row['price']; ?></td>

<td><?php echo $row['carddetails']; ?></td>

<td><?php echo $row['details']; ?></td>

<td>

<a href="delete.php?id=<?php echo $row['id']; ?>" 
onclick="return confirm('Are you sure you want to delete this product?');">

<button style="background:red;color:white;border:none;padding:6px 12px;">
Delete
</button>

</a>

</td>

</tr>

<?php
}
?>

</table>

</center>

</body>
</html>