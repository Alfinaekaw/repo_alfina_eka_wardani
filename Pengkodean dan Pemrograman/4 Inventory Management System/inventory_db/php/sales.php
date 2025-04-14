<?php
include 'db.php';

// Handle form submission for adding a new sale
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_sale'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $date = $_POST['date'];

    // Check if there is enough stock
    $check_stock_sql = "SELECT quantity FROM products WHERE id = $product_id";
    $stock_result = $conn->query($check_stock_sql)->fetch_assoc();
    if ($stock_result && $stock_result['quantity'] >= $quantity) {
        // Record the sale
        $sql = "INSERT INTO sales (product_id, quantity, date) VALUES ('$product_id', '$quantity', '$date')";
        if ($conn->query($sql) === TRUE) {
            // Update product stock
            $new_quantity = $stock_result['quantity'] - $quantity;
            $update_stock_sql = "UPDATE products SET quantity = $new_quantity WHERE id = $product_id";
            if ($conn->query($update_stock_sql) === TRUE) {
                echo "<p class='success'>Sale recorded successfully!</p>";
            } else {
                echo "<p class='error'>Error updating stock: " . $conn->error . "</p>";
            }
        } else {
            echo "<p class='error'>Error recording sale: " . $conn->error . "</p>";
        }
    } else {
        echo "<p class='error'>Not enough stock available.</p>";
    }
}

// Handle delete action
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    // Get the sale details before deleting
    $sale_sql = "SELECT product_id, quantity FROM sales WHERE id = $id";
    $sale_result = $conn->query($sale_sql)->fetch_assoc();

    if ($sale_result) {
        $product_id = $sale_result['product_id'];
        $quantity = $sale_result['quantity'];

        // Increase the product stock
        $increase_stock_sql = "UPDATE products SET quantity = quantity + $quantity WHERE id = $product_id";
        if ($conn->query($increase_stock_sql) === TRUE) {
            // Delete the sale record
            $delete_sql = "DELETE FROM sales WHERE id = $id";
            if ($conn->query($delete_sql) === TRUE) {
                echo "<p class='success'>Sale deleted successfully!</p>";
            } else {
                echo "<p class='error'>Error deleting sale: " . $conn->error . "</p>";
            }
        } else {
            echo "<p class='error'>Error updating stock: " . $conn->error . "</p>";
        }
    } else {
        echo "<p class='error'>Sale not found.</p>";
    }
}

// Fetch all sales from the database
$sql = "SELECT s.id, pr.name AS product_name, s.quantity, s.date 
        FROM sales s 
        JOIN products pr ON s.product_id = pr.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Management</title>
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
                <li><a href="sales.php" class="active">Sales</a></li>
                <li><a href="stock.php">Stock</a></li>
                <li><a href="reports.php">Reports</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h1>Sales Management</h1>

            <!-- Add Sale Form -->
            <form method="POST" action="">
                <h2>Add New Sale</h2>
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

                <button type="submit" name="add_sale">Add Sale</button>
            </form>

            <!-- Display Sales -->
            <h2>Sales List</h2>
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
                    echo "<tr><td colspan='5'>No sales found</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>