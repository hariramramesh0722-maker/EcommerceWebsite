<?php
session_start();

$servername = "localhost";
$username_db = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username_db, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : "guest";

// Handle Cancel Order
if (isset($_POST['cancel_order_id'])) {
    $order_id = intval($_POST['cancel_order_id']);

    $check = $conn->prepare("SELECT *, TIMESTAMPDIFF(HOUR, order_date, NOW()) as hours_passed FROM ordersadmin WHERE id = ? AND username = ?");
    $check->bind_param("is", $order_id, $username);
    $check->execute();
    $res = $check->get_result();
    $order = $res->fetch_assoc();
    $check->close();

    if ($order && $order['hours_passed'] <= 8) {
        $insert = $conn->prepare("INSERT INTO cancel_orders (product_name, price, details, email, mobile, delivery, quantity, address, district, username) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insert->bind_param("sdssssisss",
            $order['product_name'], $order['price'], $order['details'],
            $order['email'], $order['mobile'], $order['delivery'],
            $order['quantity'], $order['address'], $order['district'], $order['username']
        );
        $insert->execute();
        $insert->close();

        $delete = $conn->prepare("DELETE FROM ordersadmin WHERE id = ? AND username = ?");
        $delete->bind_param("is", $order_id, $username);
        $delete->execute();
        $delete->close();

        $success_msg = "Order cancelled successfully.";
    } else {
        $error_msg = "Cancellation window (8 hours) has expired for this order.";
    }
}

$stmt = $conn->prepare("SELECT *, TIMESTAMPDIFF(HOUR, order_date, NOW()) as hours_passed FROM ordersadmin WHERE username = ? ORDER BY order_date DESC");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if username exists in delivered_orders
$review_check = $conn->prepare("SELECT id FROM delivered_orders WHERE username = ? LIMIT 1");
$review_check->bind_param("s", $username);
$review_check->execute();
$review_result = $review_check->get_result();
$can_review = ($review_result->num_rows > 0);
$review_check->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Orders</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, Helvetica, sans-serif; }
body { background: #f5f6fa; }

.header {
    background: linear-gradient(135deg, #2c3e50, #34495e);
    color: white;
    padding: 20px;
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    letter-spacing: 1px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.container { max-width: 1300px; margin: 30px auto; padding: 0 20px; }

.title {
    font-size: 22px;
    margin-bottom: 20px;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 10px;
}
.title span { font-size: 14px; color: #7f8c8d; font-weight: normal; }

.alert {
    padding: 12px 18px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
    font-weight: bold;
}
.alert-success { background: #d4edda; color: #155724; border-left: 4px solid #28a745; }
.alert-error   { background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }

.table-container {
    background: white;
    border-radius: 12px;
    overflow-x: auto;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

table { width: 100%; border-collapse: collapse; min-width: 900px; }

thead tr { background: linear-gradient(135deg, #2c3e50, #34495e); }

th {
    color: white;
    padding: 15px 14px;
    text-align: left;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
}

td {
    padding: 13px 14px;
    border-bottom: 1px solid #f0f0f0;
    font-size: 13.5px;
    color: #444;
    vertical-align: middle;
}

tbody tr:hover { background: #f8f9ff; }
tbody tr:last-child td { border-bottom: none; }

.badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: bold;
    white-space: nowrap;
}
.badge-pending    { background: #fff3cd; color: #856404; }
.badge-shipped    { background: #cce5ff; color: #004085; }
.badge-delivered  { background: #d4edda; color: #155724; }
.badge-default    { background: #dff9fb; color: #0c7a8a; }

.btn-cancel {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
}
.btn-cancel:hover { background: linear-gradient(135deg, #c0392b, #a93226); transform: translateY(-1px); box-shadow: 0 3px 8px rgba(231,76,60,0.4); }

.btn-expired {
    background: #ecf0f1;
    color: #bdc3c7;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: bold;
    cursor: not-allowed;
    white-space: nowrap;
}

.timer-badge {
    display: inline-block;
    font-size: 11px;
    color: #e67e22;
    margin-top: 4px;
    font-weight: bold;
}
.timer-expired { color: #bdc3c7; }

.no-orders {
    text-align: center;
    padding: 60px 20px;
    color: #aaa;
    font-size: 16px;
}

.order-card {
    display: none;
    background: white;
    padding: 16px;
    border-radius: 12px;
    margin-bottom: 14px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    border-left: 4px solid #34495e;
}
.order-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
    flex-wrap: wrap;
    gap: 8px;
}
.order-card-header h3 { color: #2c3e50; font-size: 16px; }
.order-card p { margin: 6px 0; font-size: 13.5px; color: #555; }
.order-card p strong { color: #2c3e50; }
.card-footer { margin-top: 14px; border-top: 1px solid #eee; padding-top: 12px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; }

.review-section {
    text-align: center;
    margin: 30px auto;
    padding: 16px;
}
.review-link {
    display: inline-block;
    background: linear-gradient(135deg, #2c3e50, #34495e);
    color: white;
    padding: 10px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    font-weight: bold;
    transition: opacity 0.2s;
}
.review-link:hover { opacity: 0.85; }
.review-locked {
    color: #aaa;
    font-size: 14px;
}

@media (max-width: 768px) {
    table, .table-container { display: none; }
    .order-card { display: block; }
    .header { font-size: 18px; padding: 15px; }
    .title { font-size: 18px; }
    .container { padding: 0 12px; }
}
</style>
</head>
<body>

<div class="header">My Orders Dashboard</div>

<div class="container">

    <div class="title">
        Orders for: <?php echo htmlspecialchars($username); ?>
        <span>(You can cancel orders within 8 hours of placing)</span>
    </div>

    <?php if (isset($success_msg)): ?>
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
    <?php endif; ?>
    <?php if (isset($error_msg)): ?>
        <div class="alert alert-error"><?php echo $error_msg; ?></div>
    <?php endif; ?>

    <!-- Desktop Table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Order Date</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Details</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Delivery</th>
                    <th>Qty</th>
                    <th>Address</th>
                    <th>District</th>
                    <th>Cancel Order</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $rows = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                    $hours_left = 8 - $row['hours_passed'];
                    $can_cancel = ($row['hours_passed'] <= 8);

                    $delivery_lower = strtolower($row['delivery']);
                    if (str_contains($delivery_lower, 'pending'))   $badge_class = 'badge-pending';
                    elseif (str_contains($delivery_lower, 'ship'))  $badge_class = 'badge-shipped';
                    elseif (str_contains($delivery_lower, 'deliv')) $badge_class = 'badge-delivered';
                    else $badge_class = 'badge-default';

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['order_date']) . "</td>";
                    echo "<td><strong>" . htmlspecialchars($row['product_name']) . "</strong></td>";
                    echo "<td>₹" . htmlspecialchars($row['price']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['details']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['mobile']) . "</td>";
                    echo "<td><span class='badge {$badge_class}'>" . htmlspecialchars($row['delivery']) . "</span></td>";
                    echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['district']) . "</td>";
                    echo "<td>";
                    if ($can_cancel) {
                        echo "<form method='POST' onsubmit=\"return confirm('Cancel this order?')\">";
                        echo "<input type='hidden' name='cancel_order_id' value='" . intval($row['id']) . "'>";
                        echo "<button type='submit' class='btn-cancel'>Cancel Order</button>";
                        echo "</form>";
                        echo "<div class='timer-badge'>⏱ " . max(0, ceil($hours_left)) . "h left</div>";
                    } else {
                        echo "<button class='btn-expired' disabled>Expired</button>";
                        echo "<div class='timer-badge timer-expired'>Window closed</div>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='11'><div class='no-orders'>No orders found.</div></td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <?php foreach ($rows as $row):
        $hours_left = 8 - $row['hours_passed'];
        $can_cancel = ($row['hours_passed'] <= 8);
        $delivery_lower = strtolower($row['delivery']);
        if (str_contains($delivery_lower, 'pending'))   $badge_class = 'badge-pending';
        elseif (str_contains($delivery_lower, 'ship'))  $badge_class = 'badge-shipped';
        elseif (str_contains($delivery_lower, 'deliv')) $badge_class = 'badge-delivered';
        else $badge_class = 'badge-default';
    ?>
    <div class="order-card">
        <div class="order-card-header">
            <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
            <span class="badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($row['delivery']); ?></span>
        </div>
        <p><strong>Order Date:</strong> <?php echo htmlspecialchars($row['order_date']); ?></p>
        <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($row['price']); ?></p>
        <p><strong>Details:</strong> <?php echo htmlspecialchars($row['details']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
        <p><strong>Mobile:</strong> <?php echo htmlspecialchars($row['mobile']); ?></p>
        <p><strong>Quantity:</strong> <?php echo htmlspecialchars($row['quantity']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($row['address']); ?></p>
        <p><strong>District:</strong> <?php echo htmlspecialchars($row['district']); ?></p>
        <div class="card-footer">
            <?php if ($can_cancel): ?>
                <form method="POST" onsubmit="return confirm('Cancel this order?')">
                    <input type="hidden" name="cancel_order_id" value="<?php echo intval($row['id']); ?>">
                    <button type="submit" class="btn-cancel">Cancel Order</button>
                </form>
                <span class="timer-badge">⏱ <?php echo max(0, ceil($hours_left)); ?>h left to cancel</span>
            <?php else: ?>
                <button class="btn-expired" disabled>Expired</button>
                <span class="timer-badge timer-expired">Cancellation window closed</span>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>

</div>

<!-- Review Section -->
<div class="review-section">
    <?php if ($can_review): ?>
        <a href="review.html" class="review-link"> Please add a review after receiving the products</a>
    <?php else: ?>
        <p class="review-locked"> Review option will be available after your order is delivered.</p>
    <?php endif; ?>
</div>

<?php $stmt->close(); $conn->close(); ?>
</body>
</html>