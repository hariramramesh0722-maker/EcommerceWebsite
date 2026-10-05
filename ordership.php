
<?php
session_start();

$conn = mysqli_connect("localhost","root","","project");

if(!$conn){
    die("DB connection failed: ".mysqli_connect_error());
}

/* MOVE ORDER TO deliver_orders */

if(isset($_GET['delete_id'])){

    $delete_id = intval($_GET['delete_id']);

    mysqli_begin_transaction($conn);

    try {

        // GET ORDER
        $result = mysqli_query(
            $conn,
            "SELECT * FROM orders WHERE id = $delete_id"
        );

        if(!$result){
            throw new Exception(mysqli_error($conn));
        }

        $order = mysqli_fetch_assoc($result);

        if(!$order){
            throw new Exception("Order not found");
        }

        // INSERT INTO deliver_orders
       $insert = mysqli_query($conn,

    "INSERT INTO deliver_orders
    (product_name, price, details, email, mobile,
     delivery, quantity, address, district,
     order_date, username)

    VALUES (
        '{$order['product_name']}',
        '{$order['price']}',
        '{$order['details']}',
        '{$order['email']}',
        '{$order['mobile']}',
        '{$order['delivery']}',
        '{$order['quantity']}',
        '{$order['address']}',
        '{$order['district']}',
        '{$order['order_date']}',
        '{$order['username']}'
    )"

);

        if(!$insert){
            throw new Exception("Insert failed: " . mysqli_error($conn));
        }

        // DELETE FROM orders
        $delete = mysqli_query(
            $conn,
            "DELETE FROM orders WHERE id = $delete_id"
        );

        if(!$delete){
            throw new Exception("Delete failed: " . mysqli_error($conn));
        }

        mysqli_commit($conn);

        header("Location: ".$_SERVER['PHP_SELF']);
        exit();

    }
    catch (Exception $e){

        mysqli_rollback($conn);

        echo "ERROR: " . $e->getMessage();
        exit();
    }
}

/* GET ALL ORDERS */

$result = mysqli_query(
    $conn,
    "SELECT * FROM orders ORDER BY id ASC"
);
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<title>Packed Orders</title>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>

body{
    font-family: Arial;
    background:#f4f6f9;
}

table{
    border-collapse: collapse;
    width: 100%;
    margin: auto;
    background: white;
}

th, td{
    border: 1px solid #333;
    padding: 10px;
    text-align: center;
}

th{
    background-color: #f44336;
    color: white;
}

tr:nth-child(even){
    background-color: #f2f2f2;
}

.delete-btn{
    background: green;
    color: white;
    padding: 6px 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.delete-btn:hover{
    background: darkgreen;
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
    <br><br>
<button class="logout">
<a  style="color:white;text-decoration: none;"href="Admin.php">Back</a></button>
<h2 style="text-align:center;">
Packed Orders
</h2>

<table>

<tr>
<th>ID</th>
<th>Product</th>
<th>Details</th>
<th>Price</th>
<th>Quantity</th>
<th>Email</th>
<th>Mobile</th>
<th>Delivery</th>
<th>Address</th>
<th>District</th>
<th>Date</th>
<th>Action</th>
</tr>

<?php

while($order = mysqli_fetch_assoc($result)){

$date = date(
"d-m-Y H:i:s",
strtotime($order['order_date'])
);

echo "

<tr>

<td>{$order['id']}</td>
<td>{$order['product_name']}</td>
<td>{$order['details']}</td>
<td>{$order['price']}</td>
<td>{$order['quantity']}</td>
<td>{$order['email']}</td>
<td>{$order['mobile']}</td>
<td>{$order['delivery']}</td>
<td>{$order['address']}</td>
<td>{$order['district']}</td>
<td>$date</td>

<td>

<button
class='delete-btn'
onclick='confirmDelete({$order['id']})'
>

Update to Deliver

</button>

</td>

</tr>

";

}

?>

</table>

<script>

function confirmDelete(id){

Swal.fire({

title: 'Product packed?',
icon: 'warning',
showCancelButton: true,
confirmButtonText: 'Yes',
cancelButtonText: 'No'

}).then((result)=>{

if(result.isConfirmed){

window.location.href =
'?delete_id=' + id;

}

});

}

</script>

</body>
</html>
```
