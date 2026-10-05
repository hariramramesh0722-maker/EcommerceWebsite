<?php
// Connect to database
$conn = mysqli_connect("localhost","root","","project");
if(!$conn){
    die("Database connection failed: ".mysqli_connect_error());
}

// Fetch all delivered orders, newest first
$result = mysqli_query($conn,"SELECT * FROM delivered_orders ORDER BY delivered_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Delivered Orders</title>
<style>
    table {
        border-collapse: collapse;
        width: 100%;
        max-width: 1200px;
        margin: auto;
    }
    th, td {
        border: 1px solid #333;
        padding: 8px 12px;
        text-align: center;
    }
    th {
        background-color: red;
        color: white;
    }
    tr:nth-child(even){
        background-color: #f2f2f2;
    }
    .logout{
    width:100px;
    height:35px;
    color:white;
    background-color:red;
    border-radius:7px;
    border:white;
    margin-left:1200px;
    
}
   


</style>
</head>
<body>
    
    <h2 style="display:inline-block;color:red;">E</h2>
    <h3 style="display:inline-block;margin-left:0px;">lectroZone.in</h3>
     <br><br>
<button class="logout">
<a  style="color:white;text-decoration: none;"href="Admin.php">Back</a></button>

<h2 style="text-align:center;">Delivered Orders</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Product Name</th>
        <th>Details</th>
        <th>Price (₹)</th>
        <th>Quantity</th>
        <th>Customer Email</th>
        <th>Customer Mobile</th>
        <th>Delivery Method</th>
        <th>Address</th>
        <th>District</th>
        <th>Order Date</th>
        <th>Delivered Date</th>
    </tr>

<?php
while($order = mysqli_fetch_assoc($result)){
    // Format dates
    $order_date = date("d-m-Y H:i:s", strtotime($order['order_date']));
    $delivered_date = date("d-m-Y H:i:s", strtotime($order['delivered_date']));

    echo "<tr>
        <td>{$order['id']}</td>
        <td>".htmlspecialchars($order['product_name'])."</td>
        <td>".htmlspecialchars($order['details'])."</td>
        <td>".number_format($order['price'],2)."</td>
        <td>{$order['quantity']}</td>
        <td>".htmlspecialchars($order['email'])."</td>
        <td>".htmlspecialchars($order['mobile'])."</td>
        <td>".htmlspecialchars($order['delivery'])."</td>
        <td>".htmlspecialchars($order['address'])."</td>
        <td>".htmlspecialchars($order['district'])."</td>
        <td>$order_date</td>
        <td>$delivered_date</td>
    </tr>";
}
?>

</table>
</body>
</html>