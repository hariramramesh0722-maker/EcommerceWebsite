<?php
session_start();

$credentials_file = 'credentials.json';
$message = "";



// Check login submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['user']);
    $password = trim($_POST['password']);

    if (file_exists($credentials_file)) {
        $stored = json_decode(file_get_contents($credentials_file), true);

        if ($user === $stored['user'] && $password === $stored['password']) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user'] = $user;
            header("Location: Employee.php");
            exit();
        } else {
            $message = " Invalid user or password!";
        }
    } else {
        $message = " Credentials are not set yet!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Employee Login</title>
<link rel="stylesheet" href="Login.css">
<style>
    body { background-color: aliceblue; font-family: cambria; }
    form { background-color: lightgray; width:300px; height:210px; border-radius:20px; padding:20px; margin-top:80px; }
    input { width:90%; height:35px; margin-bottom:15px; padding:5px; }
    button { width:280px; height:40px; }
    hr{width:280px;}
    h2{
    color:red;
    display: inline-block;

}
h3{
     display: inline-block;
     margin-left: 0px;

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
    <h2>E</h2><h3>lectroZone.in</h3>
    <br><br>
    <button class="logout">
        <a style="color:white;text-decoration: none;" href="index.php">Back</a></button>
<center>
<h2>Employee Login</h2>
<?php if($message) echo "<p>$message</p>"; ?>
<form action="" method="POST">
    <label>Login</label><br>
    <hr>
    <input type="text" name="user" placeholder="User" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
</form>
</center>
</body>
</html>