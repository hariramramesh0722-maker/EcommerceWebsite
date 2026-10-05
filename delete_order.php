<?php
$conn = mysqli_connect("localhost", "root", "", "project");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

/* CHECK ORDER ID */

if (!isset($_GET['id'])) {
    die("Order ID missing.");
}

$order_id = intval($_GET['id']);

/* START TRANSACTION */

mysqli_begin_transaction($conn);

try {

    /* STEP 1: GET ORDER FROM ordersadmin */

    $stmt = $conn->prepare("SELECT * FROM ordersadmin WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        throw new Exception("Order not found.");
    }

    $order = $result->fetch_assoc();

    /* STEP 2: INSERT INTO delivered_orders */

    $insert = $conn->prepare("INSERT INTO delivered_orders
        (product_name, price, details, email, mobile,
         delivery, quantity, address, district,
         order_date, username)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $insert->bind_param(
        "sdssssissss",
        $order['product_name'],
        $order['price'],
        $order['details'],
        $order['email'],
        $order['mobile'],
        $order['delivery'],
        $order['quantity'],
        $order['address'],
        $order['district'],
        $order['order_date'],
        $order['username']
    );

    if (!$insert->execute()) {
        throw new Exception("Insert into delivered_orders failed.");
    }

    /* STEP 3: DELETE SAME ROW FROM ordersadmin */

    $delete = $conn->prepare("DELETE FROM ordersadmin WHERE id = ?");
    $delete->bind_param("i", $order_id);

    if (!$delete->execute()) {
        throw new Exception("Delete from ordersadmin failed.");
    }

    /* STEP 4: COMMIT */

    mysqli_commit($conn);

    echo "Order delivered successfully and removed from ordersadmin.";

} catch (Exception $e) {

    mysqli_rollback($conn);

    echo "Error: " . $e->getMessage();
}

mysqli_close($conn);
?>