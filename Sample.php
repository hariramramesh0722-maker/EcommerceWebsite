<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "project");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

/* Get product ID */
if (!isset($_GET['id'])) {
    die("Product ID missing");
}

$id = intval($_GET['id']);

/* Get product */
$product_query = "SELECT * FROM product WHERE id=$id";
$product_result = mysqli_query($conn, $product_query);
$product = mysqli_fetch_assoc($product_result);

/* Get logged-in username */
$username = $_SESSION['username'] ?? '';

/* Get user details */
$user_query = "SELECT * FROM signup WHERE username='$username'";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Order Product</title>

<link rel="stylesheet" href="Order_process.css">

<style>

.product-box{
width:420px;
border:1px solid lightgray;
padding:20px;
border-radius:10px;
margin-bottom:20px;
}

.image{
width:400px;
height:300px;
object-fit:cover;
border-radius:10px;
}

input{
width:300px;
height:25px;
padding:6px;
}

button{
width:200px;
height:35px;
color:white;
background-color:red;
border:1px solid white;
cursor:pointer;
}

form{
background-color:lightgrey;
width:450px;
height:450px;
}

select{
width:316px;
height:40px;
padding:6px;
}

h2{
color:red;
display:inline-block;
}

h3{
display:inline-block;
}

</style>

<script>

document.addEventListener("DOMContentLoaded", function(){

const form = document.getElementById("orderForm");

form.addEventListener("submit", function(event){

event.preventDefault();

const data = new FormData(form);

fetch(form.action,{
method:"POST",
body:data,
headers:{
"Accept":"application/json"
}
})

.then(response => {

if(response.ok){
document.getElementById("successMsg").innerHTML="✅ Order placed successfully!";
form.reset();
}else{
document.getElementById("successMsg").innerHTML="❌ Error placing order!";
}

})

.catch(error=>{
document.getElementById("successMsg").innerHTML="⚠ Network error!";
});

});

});

</script>

</head>

<body>

<h2>E</h2>
<h3>lectroZone.in</h3>

<center>

<!-- Product Display -->

<div class="product-box">

<p>Your Product</p>

<img class="image" src="uploads/<?php echo $product['image']; ?>">

<h3><?php echo $product['product_name']; ?></h3>

<p><?php echo $product['details']; ?></p>

<b>Price: ₹<?php echo $product['price']; ?></b>

</div>

<!-- Order Form -->

<div class="val">

<center>

<h2>ElectroZone Order</h2>

<form id="orderForm" action="send_order.php" method="POST">

<br><br>

<input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

<input type="hidden" name="product_name" value="<?php echo $product['product_name']; ?>">

<input type="hidden" name="price" value="<?php echo $product['price']; ?>">

<input type="email"
name="email"
placeholder="Your Email"
value="<?php echo $user['email'] ?? ''; ?>"
required>

<br><br>

<input type="tel"
name="mobile"
placeholder="Your Mobile"
value="<?php echo $user['mobile'] ?? ''; ?>"
pattern="[0-9+]{7,15}"
required>

<br><br>

<input type="text"
name="address"
placeholder="Delivery Address"
value="<?php echo $user['address'] ?? ''; ?>"
required>

<br><br>

<input type="text"
name="district"
placeholder="District"
value="Tenkasi">

<br><br>

<button type="submit">Order Now</button>

</form>

</center>

</div>

<p id="successMsg" style="color:green;font-weight:bold;"></p>

</center>

</body>
</html>