<?php
session_start();

// File to store credentials
$credentials_file = 'credentials.json';
$message = "";

// Update credentials if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['user']);
    $password = trim($_POST['password']);

    if ($user !== "" && $password !== "") {
        $data = ['user' => $user, 'password' => $password];
        file_put_contents($credentials_file, json_encode($data));
        $message = "Username and password updated successfully!";
    } else {
        $message = " Please fill both fields!";
    }
}

// Load existing credentials if file exists
$stored = ['user'=>'','password'=>''];
if (file_exists($credentials_file)) {
    $stored = json_decode(file_get_contents($credentials_file), true);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Change Credentials</title>
<link rel="stylesheet" href="Login.css">
<style>
    body { background-color: aliceblue; font-family: Arial; }
    form { background-color: lightgray; width:400px; height:250px; border-radius:20px; padding:20px; margin-top:50px; }
    input { width:90%; height:40px; margin-bottom:15px; padding:2px; }
    button { width:90%; height:40px; }
    body{
        font-family: cambria;
    }
    hr{
        width:350px;
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
    
    <h2 style="display:inline-block;color:red;">E</h2>
    <h3 style="display:inline-block;margin-left:0px;">lectroZone.in</h3>
   
    <button class="logout">
        <a style="color:white;text-decoration: none;" href="Admin.php">Back</a></button>

<center>
<h2>Change Employee User & Password</h2>
<?php if($message) echo "<p>$message</p>"; ?>
<form action="" method="POST">
    Employee Login Password and username updater
    <hr>
    <br>
    <input type="text" name="user" placeholder="User" value="<?php echo htmlspecialchars($stored['user']); ?>" required><br>
    <input type="password" name="password" placeholder="Password" value="<?php echo htmlspecialchars($stored['password']); ?>" required><br>
    <button type="submit">Update</button>
</form>
</center>
</body>
</html>