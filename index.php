<?php
$conn = mysqli_connect("localhost", "root", "", "project");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$result = mysqli_query($conn, "SELECT * FROM product");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : "guest";
    ?>

    <script>
        var username = "<?php echo htmlspecialchars($username); ?>";

        if ((username === "guest" || username === "Guest" || username === "GUEST")
            && !sessionStorage.getItem("guestAlertShown")) {

            sessionStorage.setItem("guestAlertShown", "true");

            setTimeout(function () {
                alert("Please Sign Up to continue");
                window.location.href = "Login.html";
            }, 5000);
        }

        var username = "<?php echo $username; ?>";

        // Add event listener for all links with class 'signin-link' (Login/Sign-in)
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.signin-link').forEach(function (el) {
                el.addEventListener('click', function (e) {
                    if (username !== "guest") {
                        e.preventDefault(); // Stop link
                        alert("You are already logged in! Please logout first before signing in/up.");
                    }
                });
            });

            // Also handle signup button/link with class 'signup-link'
            document.querySelectorAll('.signup-link').forEach(function (el) {
                el.addEventListener('click', function (e) {
                    if (username !== "guest") {
                        e.preventDefault();
                        alert("You are already logged in! Please logout first before signing up.");
                    }
                });
            });
        });

    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Main.css">
    <title>ElectroZone.in</title>
    <style>
        /* Simple layout for products */
        .imgcard {


            width: 290px;
            display: inline-block;
            text-align: center;
            background-color: rgb(243, 241, 241);
            border-radius: 10px;
            
            justify-content: space-between;
            height: 380px;
            margin-left: 100px;
            padding: 2px;
            transition: 0.3s;
            margin-top: 100px;
        }

        .imgcard:hover {
            transform: scale(1.05);
        }

        .img1 {
            width: 200px;
            height: 150px;

            border-radius: 10px;
            margin-left: 10px;
        }

        .card2 {
            margin-top: 10px;
            padding: 2px;
            width: 150px;
            height: 35px;
            border: none;
            background-color: red;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            margin-left: 10px;
        }

        .card2:hover {
            background-color: darkred;
        }

        .price {
            font-weight: bold;
            margin-top: 5px;
            color: green;
        }

        .btn {
            display: inline-block;
            margin-left: 100px;
        }

        .admin-btn {
            width: 100px;
            height: 35px;
            background-color: red;
            color: white;
            border: 0px solid white;
        }

        .admin-btn:hover {
            background-color: darkred;
        }
        .name{
            
        }
    </style>
</head>

<body>
    <div class="name">
    <h2 style="display:inline-block;color:red;">E</h2>
    <h3 style="display:inline-block;margin-left:0px;">lectroZone.in</h3>

    <ul>
        <div class="nav">
            <a style="color:black;" href="care.html">
                <li style="margin-left:25px;">CustomerCare</li>
            </a>
            <a style="color:black;" href="Logout.php">
                <li>Log-out</li>
            </a>
            <a href="Myorder.php">
            <li style="color:black">Myorder's</li></a>
            <a style="color:black;" href="signin.html" class="signin-link">
                <li>sign-in</li>
            </a>
        </div>
    </ul>

    <button style="margin-left:80px;" class="btn"><a style="display:inline-block; " href="Login.html"
            class="signup-link">Signup</a></button>
    <?php if (isset($_SESSION['username']) && !empty($_SESSION['username'])): ?>
        <span style="margin-left:50px;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <span style="margin-left:70px;">Welcome, Guest</span>
        
        <a href="Login.html">Sign Up</a>
    <?php endif; ?>
    </div>
    <br><br><br>
    <img class="poster" src="originalposter1.jpg">

    </div><br><br><br><br>

    <!-- Display products dynamically -->
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="imgcard">
            <br><br>
            <img class="img1" src="uploads/<?php echo $row['image']; ?>" <?php echo $row['product_name']; ?><br>
            <div class="price">₹<?php echo $row['price']; ?></div><br>
            <div class="details"><?php echo $row['carddetails']; ?></div>
            <br>



            <a href="product.php?id=<?php echo $row['id']; ?>">
                <button class="card2">Buy Now</button>
            </a>

        </div>

    <?php } ?>

    <div class="bottom">
        <center>
            <br>
            <p>
                ElectroZone.in<br>
                Copyright@team trubo tech <br>
                ElectroZone@gmail.com <br>
                contact : 9600763976,9443852621,7306422502,8056721364 <br><br>
                <button class="admin-btn" style="height:30px;"><a href="Adminlogin.html">Admin
                        Login</a></button><br><br>
                <button style="width:150px; height:30px;" class="admin-btn"><a href="loginemp.php">Employee
                        Login</a></button>

            </p>
        </center>
    </div>

</body>

</html>