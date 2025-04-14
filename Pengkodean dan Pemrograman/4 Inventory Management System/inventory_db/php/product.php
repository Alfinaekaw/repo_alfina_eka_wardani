<?php
include 'db.php';

// Handle form submission for adding a new product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $sql = "INSERT INTO products (name, price, quantity) VALUES ('$name', '$price', '$quantity')";
    if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>Product added successfully!</p>";
    } else {
        echo "<p class='error'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

// Handle form submission for updating a product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $sql = "UPDATE products SET name='$name', price='$price', quantity='$quantity' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>Product updated successfully!</p>";
    } else {
        echo "<p class='error'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

// Handle delete action
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM products WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>Product deleted successfully!</p>";
    } else {
        echo "<p class='error'>Error: " . $sql . "<br>" . $conn->error . "</p>";
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
    <title>Product Management</title>
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
                <li><a href="product.php" class="active">Product</a></li>
                <li><a href="purchase.php">Purchase</a></li>
                <li><a href="sales.php">Sales</a></li>
                <li><a href="stock.php">Stock</a></li>
                <li><a href="reports.php">Reports</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h1>Product Management</h1>

            <!-- Add/Edit Product Form -->
            <form method="POST" action="">
                <h2 id="form-title">Add New Product</h2>
                <input type="hidden" name="id" id="product_id">
                <label for="product_name">Name:</label>
                <input type="text" name="name" id="product_name" placeholder="Product Name" required>
                
                <label for="product_price">Price:</label>
                <input type="number" step="0.01" name="price" id="product_price" placeholder="Price" required>
                
                <label for="product_quantity">Quantity:</label>
                <input type="number" name="quantity" id="product_quantity" placeholder="Quantity" required>
                
                <button type="submit" name="add_product" id="add_button">Add Product</button>
                <button type="submit" name="update_product" id="update_button" style="display: none;">Update Product</button>
            </form>

            <!-- Display Products -->
            <h2>Products List</h2>
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
                                    <button onclick=\"editProduct({$row['id']}, '{$row['name']}', {$row['price']}, {$row['quantity']})\">Edit</button> |
                                    <a href='?delete_id={$row['id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No products found</td></tr>";
                }
                ?>
            </table>

            <script>
                // Function to populate the form for editing a product
                function editProduct(id, name, price, quantity) {
                    document.getElementById('product_id').value = id;
                    document.getElementById('product_name').value = name;
                    document.getElementById('product_price').value = price;
                    document.getElementById('product_quantity').value = quantity;

                    // Update the form title and button visibility
                    document.getElementById('form-title').innerText = 'Edit Product';
                    document.getElementById('add_button').style.display = 'none';
                    document.getElementById('update_button').style.display = 'inline-block';
                }

                // Reset the form when the page loads or after an action
                window.onload = function () {
                    document.getElementById('form-title').innerText = 'Add New Product';
                    document.getElementById('add_button').style.display = 'inline-block';
                    document.getElementById('update_button').style.display = 'none';
                    document.getElementById('product_id').value = '';
                    document.getElementById('product_name').value = '';
                    document.getElementById('product_price').value = '';
                    document.getElementById('product_quantity').value = '';
                };
            </script>
        </div>
    </div>
</body>
</html>