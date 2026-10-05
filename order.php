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

form.addEventListener("submit", function(event) {

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
            width: 350px;
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
        }
    </style>

</head>

<body>

    <h2>E</h2>
    <h3>lectroZone.in</h3>

    <center>

        <div class="product-box">

            <img class="image" src="uploads/<?php echo $row['image']; ?>">

            <h3><?php echo $row['product_name']; ?></h3>

            <p><?php echo $row['details']; ?></p>

            <b>Price: ₹<?php echo $row['price']; ?></b>

        </div>


        <div class="val">

            <form id="orderForm" action="https://formspree.io/f/mvzwakej" method="POST">
                <input type="hidden" name="_next" value="http://localhost/project/success.html">

                <input type="hidden" name="product_name" value="<?php echo $row['product_name']; ?>">
                <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                <input type="hidden" name="details" value="<?php echo $row['details']; ?>">

                <input type="email" name="email" placeholder="Email" required><br><br>

                <select name="delivery">
                    <option>Cash on delivery only</option>
                </select><br><br>

                <input type="number" name="quantity" placeholder="No.Of.Quantity" required><br><br>

                <input type="text" name="address" placeholder="Delivery address" required><br><br>

                <button type="submit">Order now</button>

            </form>

            <p id="successMsg" style="color:green;font-weight:bold;"></p>

            <p id="msg"></p>

            <p id="msg"></p>

        </div>

    </center>

</body>

</html>