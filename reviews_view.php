<?php

$conn = mysqli_connect("localhost", "root", "", "project");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

/* Fetch data from reviews table */

$sql = "SELECT id, productvideo, username, productname, rating 
        FROM reviews";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Reviews Table</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th {
            background: red;
            color: white;
            padding: 12px;
            font-size: 15px;
            text-align: center;
        }

        td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background: #f1f1f1;
        }

        .rating {
            font-weight: bold;
            color: #ff9800;
            font-size: 16px;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #888;
            font-weight: bold;
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
     <br><br>
    <button class="logout">
        <a style="color:white;text-decoration: none;" href="Admin.php">Back</a></button>

    <h2>Customer Reviews Details</h2>

    <table>

        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Product Name</th>
            <th>Rating</th>
            <th>Video File</th>
        </tr>

        <?php

        if ($result && mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_assoc($result)) {

                echo "<tr>";

                echo "<td>" . $row['id'] . "</td>";

                echo "<td>" . htmlspecialchars($row['username']) . "</td>";

                echo "<td>" . htmlspecialchars($row['productname']) . "</td>";

                echo "<td class='rating'>★ " . htmlspecialchars($row['rating']) . " / 5</td>";

                echo "<td>" . htmlspecialchars($row['productvideo']) . "</td>";

                echo "</tr>";
            }

        } else {

            echo "<tr>";
            echo "<td colspan='5' class='no-data'>No records found</td>";
            echo "</tr>";

        }

        mysqli_close($conn);

        ?>

    </table>

</body>

</html>