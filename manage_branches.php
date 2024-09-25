<?php
session_start();
if (!isset($_SESSION["employee_id"]) || $_SESSION["role_id"] != 1) {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Fetch branches
$branches = $conn->query("SELECT * FROM branches");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Branches</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            margin: 0;
            font-family: Arial, sans-serif;
            min-height: 100vh;
        }
        aside {
            width: 250px;
            background-color: #333;
            color: white;
            padding: 20px;
        }
        .main {
            display: flex;
            flex: 1;
        }
        .content {
            flex: 1;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .content h1 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .action-buttons a {
            margin-right: 10px;
            color: #3498DB;
            text-decoration: none;
        }
        .action-buttons a:hover {
            text-decoration: underline;
        }
        .add-branch {
            margin-bottom: 20px;
        }
        .add-branch a {
            background-color: #3498DB;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }
        .add-branch a:hover {
            background-color: #2980B9;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="main">
        <aside>
            <?php include 'admin_sidebar.php'; ?>
        </aside>
        <div class="content">
            <h1>Manage Branches</h1>
            <div class="add-branch">
                <a href="add_branch.php">Add New Branch</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($branch = $branches->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $branch['id']; ?></td>
                            <td><?php echo htmlspecialchars($branch['name']); ?></td>
                            <td><?php echo htmlspecialchars($branch['location']); ?></td>
                            <td><?php echo $branch['status'] ? 'Active' : 'Inactive'; ?></td>
                            <td class="action-buttons">
                                <a href="edit_branch.php?id=<?php echo $branch['id']; ?>">Edit</a>
                                <a href="delete_branch.php?id=<?php echo $branch['id']; ?>" onclick="return confirm('Are you sure you want to delete this branch?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <footer>
        &copy; <?php echo date("Y"); ?> Sameer Chhetri. All rights reserved.
    </footer>
</body>
</html>
