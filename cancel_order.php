<?php
$conn = mysqli_connect("localhost", "root", "", "project");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

/* Fetch data from cancel_orders table */

$sql = "SELECT 
            product_name,
            price,
            details,
            email,
            mobile,
            delivery,
            quantity,
            address,
            district,
            order_date,
            username
        FROM cancel_orders
        ORDER BY order_date DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Cancelled Orders</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f9;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        th {
            background: #d32f2f;
            color: #fff;
            padding: 12px;
            font-size: 14px;
            text-align: center;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: center;
            font-size: 13px;
        }

        tr:hover {
            background: #f1f1f1;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: red;
            font-weight: bold;
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

</head>

<body>
    <br><br>
    <button class="logout">
        <a style="color:white;text-decoration: none;" href="Admin.php">Back</a></button>
    <h2>Cancelled Orders List</h2>

    <table>

        <tr>
            <th>Product Name</th>
            <th>Price</th>
            <th>Details</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Delivery</th>
            <th>Quantity</th>
            <th>Address</th>
            <th>District</th>
            <th>Order Date</th>
            <th>Username</th>
        </tr>

        <?php

        if ($result && mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_assoc($result)) {

                echo "<tr>
                <td>{$row['product_name']}</td>
                <td>{$row['price']}</td>
                <td>{$row['details']}</td>
                <td>{$row['email']}</td>
                <td>{$row['mobile']}</td>
                <td>{$row['delivery']}</td>
                <td>{$row['quantity']}</td>
                <td>{$row['address']}</td>
                <td>{$row['district']}</td>
                <td>{$row['order_date']}</td>
                <td>{$row['username']}</td>
              </tr>";
            }

        } else {

            echo "<tr>
            <td colspan='11' class='no-data'>
                No cancelled orders found
            </td>
          </tr>";
        }

        mysqli_close($conn);

        ?>

    </table>

</body>

</html>