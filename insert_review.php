<?php

session_start();

/* Database connection */

$servername = "localhost";
$dbusername = "root";
$password = "";
$database = "project";

$conn = new mysqli($servername, $dbusername, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* Username */

    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
    } else {
        $username = "guest";
    }

    /* Product */

    if (isset($_POST['productname'])) {
        $product = $_POST['productname'];
    } else {
        echo "<script>alert('Product not selected'); window.history.back();</script>";
        exit();
    }

    /* Rating */

    if (isset($_POST['rating'])) {
        $rating = $_POST['rating'];
    } else {
        echo "<script>alert('Rating not selected'); window.history.back();</script>";
        exit();
    }

    /* File Upload Check */

    if (isset($_FILES["video"]) && $_FILES["video"]["error"] == 0) {

        $filename = $_FILES["video"]["name"];
        $tempname = $_FILES["video"]["tmp_name"];
        $filesize = $_FILES["video"]["size"];

        $folder = "uploads/" . $filename;

        /* Get extension */

        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        /* Allow only MP4 */

        if ($file_ext != "mp4") {

            echo "<script>
                    alert('Only MP4 video files are allowed. Images and MP3 files are not accepted.');
                    window.history.back();
                  </script>";
            exit();
        }

        /* Optional size limit (50MB) */

        if ($filesize > 50 * 1024 * 1024) {

            echo "<script>
                    alert('File size must be less than 50MB');
                    window.history.back();
                  </script>";
            exit();
        }

        /* Move file */

        if (move_uploaded_file($tempname, $folder)) {

            $sql = "INSERT INTO reviews 
                    (productvideo, username, productname, rating)
                    VALUES 
                    ('$filename', '$username', '$product', '$rating')";

            if ($conn->query($sql) === TRUE) {

                echo "<script>
                        alert('Video uploaded successfully');
                        window.location='index.php';
                      </script>";

            } else {

                echo "<script>
                        alert('Database Error');
                        window.history.back();
                      </script>";
            }

        } else {

            echo "<script>
                    alert('File upload failed');
                    window.history.back();
                  </script>";
        }

    } else {

        echo "<script>
                alert('No file uploaded');
                window.history.back();
              </script>";
    }

}

$conn->close();

?>