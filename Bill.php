<!DOCTYPE html>
<html>

<head>
    <title>Order Confirmation</title>

    <style>
        body {
            font-family: cambria;
            background: #f9f9f9;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            margin: 40px auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        h1 {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }

        .success {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #f2f2f2;
        }

        h2 {
            color: red;
            display: inline-block;
        }

        h3 {
            display: inline-block;
        }
    </style>
</head>

<body>

    <h2>E</h2>
    <h3>lectroZone.in</h3>

    <div class="container">

        <h1>Order Confirmation</h1>

        <p class="success">Your order has been placed successfully!</p>

        <?php
        session_start();

        if (!isset($_SESSION['order'])) {
            echo "<h2>No order data found.</h2>";
            exit();
        }

        $order = $_SESSION['order'];

        $total = $order['price'] * (int) $order['quantity'];
        ?>

        <center>
            <h2>Order Summary</h2>
        </center>

        <table>

            <tr>
                <th>Product</th>
                <td><?php echo htmlspecialchars($order['product']); ?></td>
            </tr>

            <tr>
                <th>Price</th>
                <td>₹<?php echo number_format($order['price']); ?></td>
            </tr>

            <tr>
                <th>Details</th>
                <td><?php echo nl2br(htmlspecialchars($order['details'])); ?></td>
            </tr>

            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($order['email']); ?></td>
            </tr>

            <tr>
                <th>Mobile</th>
                <td><?php echo htmlspecialchars($order['mobile']); ?></td>
            </tr>

            <tr>
                <th>Quantity</th>
                <td><?php echo (int) $order['quantity']; ?></td>
            </tr>

            <tr>
                <th>Address</th>
                <td><?php echo nl2br(htmlspecialchars($order['address'])); ?></td>
            </tr>

            <tr>
                <th>Total</th>
                <td><strong>₹<?php echo $total; ?></strong></td>
            </tr>

        </table>

        <br>

        <center>
            <p>Your order should be delivered within 2 days.</p>
            <h3>Thank you for your order!</h3>
        </center>

    </div>

</body>

</html>