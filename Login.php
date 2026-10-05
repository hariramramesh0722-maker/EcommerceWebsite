<?php
session_start();

$servername="localhost";
$username="root";
$password="";
$dbname="project";

$conn=new mysqli($servername,$username,$password,$dbname);

if($conn->connect_error){
    die("Connection failed: ".$conn->connect_error);
}

$username=$_POST['username'];
$password=$_POST['password'];
$mobile=$_POST['mobile'];
$email=$_POST['email'];
$address=$_POST['address'];

$stmt=$conn->prepare("INSERT INTO signup (username,password,mobile,email,address) VALUES (?,?,?,?,?)");
$stmt->bind_param("sssss",$username,$password,$mobile,$email,$address);

if($stmt->execute()){

    // store username in session
    $_SESSION['username']=$username;

    // redirect to home page
    header("Location:index.php");
    exit();

}
else{
    echo "Insertion error: ".$stmt->error;
}

$stmt->close();
$conn->close();
?>