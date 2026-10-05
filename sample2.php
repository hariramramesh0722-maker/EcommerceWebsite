<?php
$conn = mysqli_connect("localhost", "root", "", "project");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (!isset($_GET['id'])) {
    die("Product ID missing");
}

$id = intval($_GET['id']);

$result = mysqli_query($conn, "SELECT * FROM product WHERE id=$id");
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Order_process.css">
    <title>Order Product</title>
    <script>
        const form = document.getElementById("orderForm");

        form.addEventListener("submit", function (event) {

            event.preventDefault(); // STOP redirect

            const data = new FormData(form);

            fetch(form.action, {
                method: "POST",
                body: data,
                headers: {
                    "Accept": "application/json"
                }
            })
                .then(response => {
                    if (response.ok) {
                        document.getElementById("successMsg").innerHTML = "✅ Order placed successfully!";
                        form.reset();
                    } else {
                        document.getElementById("successMsg").innerHTML = "❌ Error placing order!";
                    }
                })
                .catch(error => {
                    document.getElementById("successMsg").innerHTML = "⚠ Network error!";
                });

        });
    </script>
    <style>
        .product-box {
            width: 420px;
            border: 1px solid lightgray;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .image {
            width: 400px;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
        }

        input {
            width: 300px;
            height: 25px;
            padding: 6px;
        }

        button {
            width: 200px;
            height: 35px;
            color: white;
            background-color: red;
            border: 1px solid white;
        }

        form {
            background-color: lightgrey;
            width: 450px;
            height: 450px;

        }

        select {
            width: 316px;
            height: 40px;
            padding: 6px;
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
    <center>
        <div class="product-box">
            <p>Your Product</p>
            <img class="image" src="uploads/<?php echo $row['image']; ?>">
            <h3><?php echo $row['product_name']; ?></h3>
            <p><?php echo $row['details']; ?></p>
            <b>Price: ₹<?php echo $row['price']; ?></b>
        </div>
        <?php
        $conn = mysqli_connect("localhost", "root", "", "project");

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "SELECT * FROM signup";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        ?>
        <div class="val">
            <center>
                <h2>ElectroZone Order</h2>


                <form action="send_order.php" method="POST">

                    <br><br>



                    <input type="email" name="email" placeholder="Your Email" value="<?php echo $row['email']; ?>"
                        required><br><br>

                    <input type="tel" name="mobile" placeholder="Your Mobile" value="<?php echo $row['mobile']; ?>"
                        pattern="[0-9+]{7,15}" required><br><br>

                    <input type="text" name="address" placeholder="Delivery Address"
                        value="<?php echo $row['address']; ?>" required><br><br>

                    <input type="text" name="district" placeholder="District" value="Tenkasi"><br><br>

                    <button type="submit">Order Now</button>

                </form>
            </center>
        </div>
        <p id="successMsg" style="color:green;font-weight:bold;"></p>
        <p id="msg"></p>
        <p id="msg"></p>

        </div>
<h2>E</h2>
    <h3>lectroZone.in</h3>
    <center>
        <div class="product-box">
            <p>Your Product</p>
            <img class="image" src="uploads/<?php echo $row['image']; ?>">
            <h3><?php echo $row['product_name']; ?></h3>
            <p><?php echo $row['details']; ?></p>
            <b>Price: ₹<?php echo $row['price']; ?></b>
        </div>
    </center>

</body>

</html>