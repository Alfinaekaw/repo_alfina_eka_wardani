<?php
include 'db.php';

// Handle form submission for adding a new user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $group = $_POST['group'];
    $status = $_POST['status'];

    $sql = "INSERT INTO users (username, password, group_name, status) VALUES ('$username', '$password', '$group', '$status')";
    if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>User added successfully!</p>";
    } else {
        echo "<p class='error'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

// Handle form submission for updating a user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $group = $_POST['group'];
    $status = $_POST['status'];

    $sql = "UPDATE users SET username='$username', password='$password', group_name='$group', status='$status' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>User updated successfully!</p>";
    } else {
        echo "<p class='error'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

// Handle delete action
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM users WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>User deleted successfully!</p>";
    } else {
        echo "<p class='error'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

// Fetch all users from the database
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>KANDI Inventory</h2>
            <ul>
                <li><a href="../index.html">Dashboard</a></li>
                <li><a href="user_management.php" class="active">User Management</a></li>
                <li><a href="product.php">Product</a></li>
                <li><a href="purchase.php">Purchase</a></li>
                <li><a href="sales.php">Sales</a></li>
                <li><a href="stock.php">Stock</a></li>
                <li><a href="reports.php">Reports</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h1>User Management</h1>

            <!-- Add/Edit User Form -->
            <form method="POST" action="">
                <h2 id="form-title">Add New User</h2>
                <input type="hidden" name="id" id="user_id">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" placeholder="Username" required>
                
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Password" required>
                
                <label for="group">Group:</label>
                <select name="group" id="group" required>
                    <option value="admin">Admin</option>
                    <option value="employee">Employee</option>
                </select>
                
                <label for="status">Status:</label>
                <select name="status" id="status" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                
                <button type="submit" name="add_user" id="add_button">Add User</button>
                <button type="submit" name="update_user" id="update_button" style="display: none;">Update User</button>
            </form>

            <!-- Display Users -->
            <h2>Users List</h2>
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Group</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['username']}</td>
                                <td>{$row['group_name']}</td>
                                <td>{$row['status']}</td>
                                <td>
                                    <button onclick=\"editUser({$row['id']}, '{$row['username']}', '{$row['group_name']}', '{$row['status']}')\">Edit</button> |
                                    <a href='?delete_id={$row['id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No users found</td></tr>";
                }
                ?>
            </table>

            <script>
                // Function to populate the form for editing a user
                function editUser(id, username, group, status) {
                    document.getElementById('user_id').value = id;
                    document.getElementById('username').value = username;
                    document.getElementById('group').value = group;
                    document.getElementById('status').value = status;

                    // Update the form title and button visibility
                    document.getElementById('form-title').innerText = 'Edit User';
                    document.getElementById('add_button').style.display = 'none';
                    document.getElementById('update_button').style.display = 'inline-block';
                }

                // Reset the form when the page loads or after an action
                window.onload = function () {
                    document.getElementById('form-title').innerText = 'Add New User';
                    document.getElementById('add_button').style.display = 'inline-block';
                    document.getElementById('update_button').style.display = 'none';
                    document.getElementById('user_id').value = '';
                    document.getElementById('username').value = '';
                    document.getElementById('password').value = '';
                    document.getElementById('group').value = '';
                    document.getElementById('status').value = '';
                };
            </script>
        </div>
    </div>
</body>
</html>