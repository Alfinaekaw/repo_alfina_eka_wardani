<?php
include 'db.php';

// Handle form submission for filtering reports
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['filter_report'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Fetch filtered purchases
    $purchase_sql = "SELECT p.id, pr.name AS product_name, p.quantity, p.date 
                     FROM purchases p 
                     JOIN products pr ON p.product_id = pr.id 
                     WHERE p.date BETWEEN '$start_date' AND '$end_date'";
    $purchase_result = $conn->query($purchase_sql);

    // Fetch filtered sales
    $sales_sql = "SELECT s.id, pr.name AS product_name, s.quantity, s.date 
                  FROM sales s 
                  JOIN products pr ON s.product_id = pr.id 
                  WHERE s.date BETWEEN '$start_date' AND '$end_date'";
    $sales_result = $conn->query($sales_sql);
} else {
    // Default: Fetch all purchases and sales
    $purchase_sql = "SELECT p.id, pr.name AS product_name, p.quantity, p.date 
                     FROM purchases p 
                     JOIN products pr ON p.product_id = pr.id";
    $purchase_result = $conn->query($purchase_sql);

    $sales_sql = "SELECT s.id, pr.name AS product_name, s.quantity, s.date 
                  FROM sales s 
                  JOIN products pr ON s.product_id = pr.id";
    $sales_result = $conn->query($sales_sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>KANDI Inventory</h2>
            <ul>
                <li><a href="../index.html">Dashboard</a></li>
                <li><a href="user_management.php">User Management</a></li>
                <li><a href="product.php">Product</a></li>
                <li><a href="purchase.php">Purchase</a></li>
                <li><a href="sales.php">Sales</a></li>
                <li><a href="stock.php">Stock</a></li>
                <li><a href="reports.php" class="active">Reports</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h1>Reports</h1>

            <!-- Filter Form -->
            <form method="POST" action="">
                <h2>Filter Reports</h2>
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" id="start_date" required>

                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" id="end_date" required>

                <button type="submit" name="filter_report">Filter</button>
            </form>

            <!-- Purchases Report -->
            <h2>Purchases Report</h2>
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Date</th>
                </tr>
                <?php
                if ($purchase_result->num_rows > 0) {
                    while ($row = $purchase_result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['product_name']}</td>
                                <td>{$row['quantity']}</td>
                                <td>{$row['date']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No purchases found</td></tr>";
                }
                ?>
            </table>

            <!-- Sales Report -->
            <h2>Sales Report</h2>
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Date</th>
                </tr>
                <?php
                if ($sales_result->num_rows > 0) {
                    while ($row = $sales_result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['product_name']}</td>
                                <td>{$row['quantity']}</td>
                                <td>{$row['date']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No sales found</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>