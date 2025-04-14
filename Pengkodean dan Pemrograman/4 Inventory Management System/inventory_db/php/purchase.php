<?php
include 'db.php';

// Handle form submission for adding a new purchase
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_purchase'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $date = $_POST['date'];

    // Update product quantity in stock
    $check_stock_sql = "SELECT quantity FROM products WHERE id = $product_id";
    $stock_result = $conn->query($check_stock_sql)->fetch_assoc();
    if ($stock_result) {
        $new_quantity = $stock_result['quantity'] + $quantity;

        // Record the purchase
        $sql = "INSERT INTO purchases (product_id, quantity, date) VALUES ('$product_id', '$quantity', '$date')";
        if ($conn->query($sql) === TRUE) {
            // Update product stock
            $update_stock_sql = "UPDATE products SET quantity = $new_quantity WHERE id = $product_id";
            if ($conn->query($update_stock_sql) === TRUE) {
                echo "<p class='success'>Purchase recorded successfully!</p>";
            } else {
                echo "<p class='error'>Error updating stock: " . $conn->error . "</p>";
            }
        } else {
            echo "<p class='error'>Error recording purchase: " . $conn->error . "</p>";
        }
    } else {
        echo "<p class='error'>Product not found.</p>";
    }
}

// Handle delete action
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    // Get the purchase details before deleting
    $purchase_sql = "SELECT product_id, quantity FROM purchases WHERE id = $id";
    $purchase_result = $conn->query($purchase_sql)->fetch_assoc();

    if ($purchase_result) {
        $product_id = $purchase_result['product_id'];
        $quantity = $purchase_result['quantity'];

        // Reduce the product stock
        $reduce_stock_sql = "UPDATE products SET quantity = quantity - $quantity WHERE id = $product_id";
        if ($conn->query($reduce_stock_sql) === TRUE) {
            // Delete the purchase record
            $delete_sql = "DELETE FROM purchases WHERE id = $id";
            if ($conn->query($delete_sql) === TRUE) {
                echo "<p class='success'>Purchase deleted successfully!</p>";
            } else {
                echo "<p class='error'>Error deleting purchase: " . $conn->error . "</p>";
            }
        } else {
            echo "<p class='error'>Error updating stock: " . $conn->error . "</p>";
        }
    } else {
        echo "<p class='error'>Purchase not found.</p>";
    }
}

// Fetch all purchases from the database
$sql = "SELECT p.id, pr.name AS product_name, p.quantity, p.date 
        FROM purchases p 
        JOIN products pr ON p.product_id = pr.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Management</title>
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
                <li><a href="purchase.php" class="active">Purchase</a></li>
                <li><a href="sales.php">Sales</a></li>
                <li><a href="stock.php">Stock</a></li>
                <li><a href="reports.php">Reports</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h1>Purchase Management</h1>

            <!-- Add Purchase Form -->
            <form method="POST" action="">
                <h2>Add New Purchase</h2>
                <label for="product_id">Product:</label>
                <select name="product_id" id="product_id" required>
                    <?php
                    $products_sql = "SELECT * FROM products";
                    $products_result = $conn->query($products_sql);
                    while ($product = $products_result->fetch_assoc()) {
                        echo "<option value='{$product['id']}'>{$product['name']}</option>";
                    }
                    ?>
                </select>

                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" id="quantity" placeholder="Quantity" required>

                <label for="date">Date:</label>
                <input type="date" name="date" id="date" required>

                <button type="submit" name="add_purchase">Add Purchase</button>
            </form>

            <!-- Display Purchases -->
            <h2>Purchases List</h2>
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['product_name']}</td>
                                <td>{$row['quantity']}</td>
                                <td>{$row['date']}</td>
                                <td>
                                    <a href='?delete_id={$row['id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No purchases found</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>