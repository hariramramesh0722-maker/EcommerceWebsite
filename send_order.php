<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

$conn = mysqli_connect("localhost","root","","project");
if(!$conn){ die("DB connection failed: ".mysqli_connect_error()); }

$username=$_POST['username'];
$product = $_POST['product_name'];
$price = $_POST['price'];
$details = $_POST['details'];
$delivery = $_POST['delivery'];
$district = $_POST['district'];
$email = $_POST['email'];  
$mobile = $_POST['mobile'] ;    
$quantity = $_POST['quantity'];
$address = $_POST['address'];


$stmt = $conn->prepare("INSERT INTO orders 
    (product_name, price, details, email, mobile, delivery, quantity, address, district,username) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ? , ?)");
$stmt->bind_param("sdssssisss", $product, $price, $details, $email, $mobile, $delivery, $quantity, $address, $district,$username);

if(!$stmt->execute()){
    die("Error placing order: ".$stmt->error);
}
$stmt = $conn->prepare("INSERT INTO ordersadmin
    (product_name, price, details, email, mobile, delivery, quantity, address, district,username) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ? ,?)");
$stmt->bind_param("sdssssisss", $product, $price, $details, $email, $mobile, $delivery, $quantity, $address, $district,$username);

if(!$stmt->execute()){
    die("Error placing order: ".$stmt->error);
}
 

$_SESSION['order'] = [
    'product' => $product,
    'price' => $price,
    'details' => $details,
    'email' => $email,
    'mobile' => $mobile,
    'quantity' => $quantity,
    'address' => $address
];


$mail = new PHPMailer(true);

try {
  
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'harizzr9600@gmail.com';       
    $mail->Password = 'wwoa qkss ylwh cayk';        
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

   
    $mail->setFrom($email, 'Customer');           
    $mail->addAddress('harizzr9600@gmail.com', 'ElectroZone'); 
   
    $mail->addReplyTo($email, 'Customer');

    
    $mail->isHTML(true);
    $mail->Subject = "New Order Received";
    $mail->Body = "
        <h2>New Order Details</h2>
        <b>Username:</b>$username<br>
        <b>Product:</b> $product<br>
        <b>Price:</b> ₹$price<br>
        <b>Details:</b> $details<br>
        <b>Customer Email:</b> $email<br>
        <b>Mobile No:</b>$mobile<br>
        <b>Quantity:</b> $quantity<br>
        <b>Address:</b> $address<br>
    ";

    $mail->send();
   header("location:Bill.php");

} catch (Exception $e) {
    echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>