<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <style>
        button
        {
            width:250px;
            height:35px;
            color:white;
            background-color:red;
            border:1px solid white;
            
            border-radius: 4px;
        }
        button:hover{
            background-color: darkred;
        }
        h2{
            color:red;
            display:inline-block;

        }
        h3{
            display:inline-block;
            
        }
        a{
            color:white;
            text-decoration: none;
        }
        .logout{
            color:black; 
            margin-left:1100px;
            color:black;
            width:100px;
            

        }
        .log-btn{
            background-color: red;
            color:white;
            border:1px solid white;
            width:100px;
            height:30px;
            display:inline-block;
        }
        .back{
            background-color:lightgray;
            width:450px;
            height:500px;
             transition: 0.3s;
            border-radius: 6px;
            box-shadow:lightgrey ;
            
        }
        .back:hover{
            
            transform:scale(1.02);
            box-shadow:lightcoral outset;
        }
    </style>
</head>
<body>
    <h2>E</h2><h3>lectroZone.in</h3>
    <button class="logout">
    <a  href="index.php">Logout</a>
</button>
    <br><br><br><br><br>
    <center>
    <div class="back">
        <br><br><br><br>
    
    <button>
    <a href="inde.php">Add products<a>
    </a>
</button>
<br><br>
<button>
<a href="Allproduct.php">All ordered products</a>
</button>
<br><br>
   <button>
<a href="ordership.php">Order shipped</a>
</button>
<br><br>
<button>
<a href="Employee.php">Employee</a>
</button>
<br><br>

 



<button>
<a href="deliver.php">Deliverd products</a>
</button>

<br><br>

<button>
<a href="cancel_order.php">Canceled orders</a>
</button>
<br><br>

<button>
<a href="reviews_view.php">Customer reviews</a>
</button>
<br><br>
<button style="width:400px;">
<a  href="set_credentials.php">Employee username and password allocator</a>
</button>
<br><br>
</center>
</div>
</body>
</html>