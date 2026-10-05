<?php
$admin = isset($_POST['admin']) ? trim($_POST['admin']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if($admin == "ElectroZone" && $password == "ElectroZone") {
    header("location: Admin.php");
    exit;
} else {
    echo '<script>alert("Please enter the username or password correctly"); window.history.back();</script>';
}
?>