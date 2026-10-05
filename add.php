<?php
// products.php - Display products as cards
$conn = mysqli_connect("localhost", "root", "", "script_project");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$result = mysqli_query($conn, "SELECT * FROM product");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product List - ElectroZone.in</title>
    <link rel="stylesheet" href="Main.css">
</head>
<body>

<center>
<h2>ElectroZone.in - Product List</h2>
<br>

<?php
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
?>
        <!-- Product Card Start -->
        <div style="border: 1px solid #ccc; padding: 15px; margin: 10px; width: 250px; display: inline-block; vertical-align: top;">
            
            <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" 
                 width="200" 
                 alt="<?php echo htmlspecialchars($row['product_name']); ?>">
            <br><br>
            
            <b>Name:</b> <?php echo htmlspecialchars($row['product_name']); ?>
            <br><br>
            
            <b>Price:</b> ₹<?php echo htmlspecialchars($row['price']); ?>
            <br><br>
            
            <b>Quantity:</b> <?php echo htmlspecialchars($row['quantity']); ?>
            <br><br>
            
            <b>Details:</b> 
            <br>
            <?php echo nl2br(htmlspecialchars($row['details']));
            
             ?>
            
            <br><br>
            
        </div>
        <!-- Product Card End -->
<?php
    }
} else {
    echo "<p>No products found.</p>";
}
?>

</center>

</body>
</html>

<?php
mysqli_close($conn);
?>