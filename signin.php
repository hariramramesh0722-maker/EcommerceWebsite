<?php
session_start();

$conn = new mysqli("localhost","root","","project");

if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];

// check if username exists
$sql = "SELECT * FROM signup WHERE username='$username'";
$result = $conn->query($sql);

if($result->num_rows > 0){

    // store username in session
    $_SESSION['username'] = $username;

    echo "<script>
            alert('Login Success');
            window.location.href='index.php';
          </script>";

}
else{

    echo "<script>
            alert('Username not exists');
            window.location.href='Login.html';
          </script>";

}

$conn->close();
?>