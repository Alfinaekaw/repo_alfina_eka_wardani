<?php
include 'db.php';

// Handle form submission for updating stock
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_stock'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['new_quantity'];

    // Update product stock
    $sql = "UPDATE products SET quantity = '$new_quantity' WHERE id = '$product_id'";
    if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>Stock updated successfully!</p>";
    } else {
        echo "<p class='error'>Error updating stock: " . $conn->error . "</p>";
    }
}

// Fetch all products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stock Management</title>
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
                <li><a href="stock.php" class="active">Stock</a></li>
                <li><a href="reports.php">Reports</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h1>Stock Management</h1>

            <!-- Display Stock -->
            <h2>Current Stock</h2>
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['price']}</td>
                                <td>{$row['quantity']}</td>
                                <td>
                                    <button onclick=\"editStock({$row['id']}, '{$row['name']}', {$row['quantity']})\">Edit Stock</button>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No products found</td></tr>";
                }
                ?>
            </table>

            <!-- Edit Stock Form -->
            <form method="POST" action="" id="edit-stock-form" style="display: none;">
                <h2 id="form-title">Edit Stock</h2>
                <input type="hidden" name="product_id" id="stock_product_id">
                <label for="product_name">Product Name:</label>
                <input type="text" name="product_name" id="stock_product_name" readonly>
                
                <label for="new_quantity">New Quantity:</label>
                <input type="number" name="new_quantity" id="new_quantity" placeholder="Enter new quantity" required>
                
                <button type="submit" name="update_stock">Update Stock</button>
            </form>

            <script>
                // Function to populate the form for editing stock
                function editStock(id, name, currentQuantity) {
                    document.getElementById('stock_product_id').value = id;
                    document.getElementById('stock_product_name').value = name;
                    document.getElementById('new_quantity').value = currentQuantity;

                    // Show the edit stock form
                    document.getElementById('edit-stock-form').style.display = 'block';
                }

                // Reset the form when the page loads or after an action
                window.onload = function () {
                    document.getElementById('edit-stock-form').style.display = 'none';
                };
            </script>
        </div>
    </div>
</body>
</html>